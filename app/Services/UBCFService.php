<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UBCFService
{
    public function test()
    {
        return "Service jalan!";
    }

    // public function getUserRatings(){
    //     $ratings = DB::table('tEvaluations')
    //             ->whereNotNull('target_committee')
    //             ->select([
    //                 'evaluator_id as idUser',
    //                 'target_committee as committee',
    //                 'ratings'
    //             ])
    //             ->get();

    //     return $ratings;
    // }

    public function buildMatrix(){
        $users = DB::table('tRegistrations')
        ->where('status', 'diterima')
        ->distinct()
        ->pluck('idUsers');

        $matrix = [];

        foreach($users as $userId){
            $divisions = DB::table('tRegistrations as r')
                            ->join('tCommittees as c', 'r.idCommittees', 'c.idCommittees')
                            ->where('r.idUsers', $userId)
                            ->where('r.status', 'diterima')
                            ->select(
                                'r.idDivisions',
                                DB::raw('COUNT(r.idRegistrations) as freq'),
                                DB::raw('MAX(c.end_period) as latest')
                            )
                            ->groupBy('r.idDivisions')
                            ->orderByDesc('freq')
                            ->orderByDesc('latest')
                            ->get();

            $rank = count($divisions);

            $matrix[$userId] = [];

            foreach($divisions as $division){
                $matrix[$userId][$division->idDivisions] = $rank;
                $rank--;
            }
        }

        return $matrix;
    }

    public function getUserAverage($userRatings){
        return array_sum($userRatings) / count($userRatings);
    }

    public function pearsonSimilarity($userA, $userB){
        // array_intersect_key => nyari isi elemen array yang punya key sama
        $commonItems = array_intersect_key($userA, $userB);

        if(count($commonItems) == 0) return 0;

        $avgA = $this->getUserAverage($userA);
        $avgB = $this->getUserAverage($userB);

        $numerator = 0;
        $denomA = 0;
        $denomB = 0;

        foreach($commonItems as $item =>$rating){
            $diffA = $userA[$item] - $avgA;
            $diffB = $userB[$item] - $avgB;

            $numerator += $diffA * $diffB;
            $denomA += pow($diffA, 2);
            $denomB += pow($diffB, 2);
        }

        if($denomA == 0 || $denomB == 0) return 0;

        return $numerator / (sqrt($denomA) * sqrt($denomB));

    }

    // public function cosineSimilarity($userA, $userB){
    //     $commonItems = array_intersect_key($userA, $userB);

    //     if(count($commonItems) == 0) return 0;

    //     $dotProduct = 0;
    //     $normA = 0;
    //     $normB = 0;

    //     foreach($commonItems as $item => $rating){
    //         $dotProduct += $userA[$item] * $userB[$item];
    //         $normA += pow($userA[$item], 2);
    //         $normB += pow($userB[$item], 2);
    //     }

    //     if($normA == 0 || $normB == 0) return 0;

    //     return $dotProduct / (sqrt($normA) * sqrt($normB));
    // }

    public function calculateAllSimilarities($matrix){
        // hapus similariti lama biar nga ke duplicate
        DB::table('tUserSimilarities')->delete();

        $similarities = [];

        foreach($matrix as $user1 => $ratings1){
            foreach($matrix as $user2 => $ratings2){
                if($user1 == $user2) continue;

                $sim = $this->pearsonSimilarity($ratings1, $ratings2);

                Log::info('=== SIMILARITY ===', [
                    'user1' => $user1,
                    'user2' => $user2,
                    'ratings1' => $ratings1,
                    'ratings2' => $ratings2,
                    'similarity' => $sim
                ]);

                // cuman ambil similarity positif doang
                if($sim > 0){
                    $similarities[] = [
                        'idUsers1' => $user1,
                        'idUsers2' => $user2,
                        'similarity_score' => $sim,
                    ];
                }
                
            }
        }

        DB::table('tUserSimilarities')->insert($similarities);

        return $similarities;
    }

    public function calculateCommitteeContributions($idUser, $similarities, $userPreferences)
    {
        $committeeScores = [];
        $committeeSimilaritySums = [];

        // Committee yang pernah diikuti user
        $userCommittees = DB::table('tRegistrations')
            ->where('idUsers', $idUser)
            ->where('status', 'diterima')
            ->pluck('idCommittees')
            ->toArray();

        foreach ($similarities as $sim) {

            if ($sim['idUsers1'] != $idUser) {
                continue;
            }

            $neighbor = $sim['idUsers2'];
            $similarity = $sim['similarity_score'];

            // Skip similarity <= 0
            if ($similarity <= 0) {
                continue;
            }

            // Ambil committee aktif milik neighbor
            $neighborCommittees = DB::table('tRegistrations as r')
                ->join('tCommittees as c', 'r.idCommittees', '=', 'c.idCommittees')
                ->where('r.idUsers', $neighbor)
                ->where('r.status', 'diterima')
                ->where('c.is_active', 1)
                ->where('c.is_published', 1)
                ->whereDate('c.end_regis', '>=', now())
                ->distinct()
                ->pluck('r.idCommittees');

            foreach ($neighborCommittees as $committeeId) {

                // Jangan rekomendasikan committee yang pernah diikuti user
                if (in_array($committeeId, $userCommittees)) {
                    continue;
                }

                $committeeDivisions = DB::table('tListDivisions')
                    ->where('idCommittees', $committeeId)
                    ->pluck('idDivisions')
                    ->toArray();

                // Cari bobot preferensi terbesar
                $matchedWeight = 0;

                foreach ($committeeDivisions as $divisionId) {

                    if (isset($userPreferences[$divisionId])) {

                        $matchedWeight = max(
                            $matchedWeight,
                            $userPreferences[$divisionId]
                        );
                    }
                }

                // Tidak ada divisi yang cocok
                if ($matchedWeight == 0) {
                    continue;
                }

                if (!isset($committeeScores[$committeeId])) {

                    $committeeScores[$committeeId] = 0;
                    $committeeSimilaritySums[$committeeId] = 0;
                }

                // Pembilang
                $committeeScores[$committeeId] +=
                    $similarity * $matchedWeight;

                // Penyebut
                $committeeSimilaritySums[$committeeId] +=
                    abs($similarity);

                Log::info('Committee Contribution', [
                    'committee' => $committeeId,
                    'neighbor' => $neighbor,
                    'similarity' => $similarity,
                    'weight' => $matchedWeight,
                    'contribution' => $similarity * $matchedWeight
                ]);
            }
        }

        return [
            'scores' => $committeeScores,
            'similarities' => $committeeSimilaritySums
        ];
    }

    public function predictCommitteeScores($committeeData)
    {
        $committeeScores = $committeeData['scores'];
        $committeeSimilaritySums = $committeeData['similarities'];

        foreach ($committeeScores as $committeeId => $score) {

            if ($committeeSimilaritySums[$committeeId] > 0) {

                $committeeScores[$committeeId] =
                    $score /
                    $committeeSimilaritySums[$committeeId];

            }
        }

        arsort($committeeScores);

        Log::info('Final Committee Scores', [
            'scores' => $committeeScores
        ]);

        return $committeeScores;
    }
    // public function getUserCommitteeKeywords($idUser)
    // {
    //     $committees = DB::table('tEvaluations')
    //         ->where('evaluator_id', $idUser)
    //         ->whereNotNull('target_committee')
    //         ->pluck('target_committee');

    //     return DB::table('tListDivisionKeywords as lk')
    //         ->join('tListDivisions as ld', function ($join) {
    //             $join->on('ld.idDivisions', '=', 'lk.idDivisions')
    //                 ->on('ld.idCommittees', '=', 'lk.idCommittees');
    //         })
    //         ->whereIn('ld.idCommittees', $committees)
    //         ->pluck('lk.idKeywords')
    //         ->unique()
    //         ->toArray();
    // }

    // public function getRecommendedKeywords($idUser)
    // {
    //     $ratings = $this->getUserRatings();

    //     $matrix = $this->buildMatrix($ratings);

    //     $similarities = $this->calculateAllSimilarities($matrix);

    //     $keywordScores = [];

    //     // $userKeywords = $this->getUserCommitteeKeywords($idUser);

    //     foreach ($similarities as $sim) {

    //         if ($sim['idUsers1'] != $idUser) {
    //             continue;
    //         }

    //         $neighbor = $sim['idUsers2'];

    //         // Ambil committee + divisi yang pernah diikuti neighbor
    //         $neighborRegistrations = DB::table('tRegistrations')
    //             ->where('idUsers', $neighbor)
    //             ->where('status', 'diterima')
    //             ->select([
    //                 'idCommittees',
    //                 'idDivisions'
    //             ])
    //             ->get();

    //         foreach ($neighborRegistrations as $registration) {

    //             // Ambil rating neighbor terhadap committee tersebut
    //             $rating = DB::table('tEvaluations')
    //                 ->where('evaluator_id', $neighbor)
    //                 ->where('target_committee', $registration->idCommittees)
    //                 ->value('ratings');

    //             if (!$rating) {
    //                 continue;
    //             }

    //             // Ambil keyword dari divisi yang benar-benar diikuti
    //             $divisionKeywords = DB::table('tListDivisionKeywords')
    //                 ->where('idCommittees', $registration->idCommittees)
    //                 ->where('idDivisions', $registration->idDivisions)
    //                 ->pluck('idKeywords');

    //             foreach ($divisionKeywords as $keywordId) {

    //                 if (!isset($keywordScores[$keywordId])) {
    //                     $keywordScores[$keywordId] = 0;
    //                 }

    //                 $keywordScores[$keywordId] +=
    //                     $sim['similarity_score'] *
    //                     $rating;
    //             }
    //         }
    //     }

    //     arsort($keywordScores);

    //     return $keywordScores;
    // }

    public function getPreferredDivisions($idUser)
    {
        $divisions = DB::table('tRegistrations as r')
            ->join('tCommittees as c', 'r.idCommittees', '=', 'c.idCommittees')
            ->where('r.idUsers', $idUser)
            ->where('r.status', 'diterima')
            ->select(
                'r.idDivisions',
                DB::raw('COUNT(*) as freq'),
                DB::raw('MAX(c.end_period) as latest')
            )
            ->groupBy('r.idDivisions')
            ->orderByDesc('freq')
            ->orderByDesc('latest')
            ->get();

        $rank = count($divisions);

        $preferences = [];

        foreach ($divisions as $division) {
            $preferences[$division->idDivisions] = $rank;
            $rank--;
        }

        Log::info('=== USER PREFERENCES ===', [
            'user' => $idUser,
            'preferences' => $preferences
        ]);

        return $preferences;
    }

    //menghitung skor committee
    public function getCommitteeScores($idUser)
    {
         $matrix = $this->buildMatrix();

        $similarities =
            $this->calculateAllSimilarities($matrix);

        $userPreferences =
            $this->getPreferredDivisions($idUser);

        $committeeData =
            $this->calculateCommitteeContributions(
                $idUser,
                $similarities,
                $userPreferences
            );

        return $this->predictCommitteeScores(
            $committeeData
        );
    }

    // nyimpan ke db
    public function generateRecommendations($idUser)
    {
        DB::table('tRecommendations')
            ->where('idUsers', $idUser)
            ->delete();

        $scores = $this->getCommitteeScores($idUser);

        $topRecommendations = [];

        $limit = 10; 

        foreach (
            array_slice($scores, 0, $limit, true)
            as $committeeId => $score
        ) {

            $topRecommendations[] = [
                'idUsers' => $idUser,
                'idCommittees' => $committeeId,
                'predicted_score' => $score
            ];
        }

        if (!empty($topRecommendations)) {
            DB::table('tRecommendations')
                ->insert($topRecommendations);
        }

        Log::info('=== FINAL RECOMMENDATIONS ===', [
            'user' => $idUser,
            'recommendations' => $topRecommendations
        ]);

        return $topRecommendations;
    }
    
    // ngambil hasil rekomendasi dr db
    public function getCommitteeRecommendations($idUser){
        return DB::table('tRecommendations as r')
        ->join(
            'tCommittees as c',
            'r.idCommittees',
            '=',
            'c.idCommittees'
        )
        ->where('r.idUsers', $idUser)
        ->orderByDesc('r.predicted_score')
        ->select([
            'c.*',
            'r.predicted_score'
        ])
        ->get();
    }

    public function getCalculationDetail($idUser)
    {
        $matrix = $this->buildMatrix();
        $similarities = $this->calculateAllSimilarities($matrix);
        $userPreferences = $this->getPreferredDivisions($idUser);

        // Gunakan fungsi yang sudah ada
        $committeeData = $this->calculateCommitteeContributions(
            $idUser,
            $similarities,
            $userPreferences
        );

        $predictedScores = $this->predictCommitteeScores($committeeData);

        $details = [];

        $userCommittees = DB::table('tRegistrations')
            ->where('idUsers', $idUser)
            ->where('status', 'diterima')
            ->pluck('idCommittees')
            ->toArray();

        foreach ($similarities as $sim) {

            if ($sim['idUsers1'] != $idUser) {
                continue;
            }

            $neighbor = $sim['idUsers2'];
            $similarity = $sim['similarity_score'];

            if ($similarity <= 0) {
                continue;
            }

            $neighborCommittees = DB::table('tRegistrations as r')
                ->join('tCommittees as c', 'r.idCommittees', '=', 'c.idCommittees')
                ->where('r.idUsers', $neighbor)
                ->where('r.status', 'diterima')
                ->where('c.is_active', 1)
                ->where('c.is_published', 1)
                ->whereDate('c.end_regis', '>=', now())
                ->distinct()
                ->pluck('r.idCommittees');

            foreach ($neighborCommittees as $committeeId) {

                if (in_array($committeeId, $userCommittees)) {
                    continue;
                }

                $committeeDivisions = DB::table('tListDivisions')
                    ->where('idCommittees', $committeeId)
                    ->pluck('idDivisions')
                    ->toArray();

                $matchedWeight = 0;
                $matchedDivision = null;

                foreach ($committeeDivisions as $divisionId) {

                    if (
                        isset($userPreferences[$divisionId]) &&
                        $userPreferences[$divisionId] > $matchedWeight
                    ) {

                        $matchedWeight = $userPreferences[$divisionId];
                        $matchedDivision = $divisionId;
                    }
                }

                if ($matchedWeight == 0) {
                    continue;
                }

                $contribution = $similarity * $matchedWeight;

                $details[] = [

                    'neighbor' => $neighbor,

                    'committee' => $committeeId,

                    'matched_division' => $matchedDivision,

                    'similarity' => round($similarity, 4),

                    'weight' => $matchedWeight,

                    'contribution' => round($contribution, 4),

                    // Diambil dari calculateCommitteeScores()
                    'total_contribution' => round(
                        $committeeData['scores'][$committeeId],
                        4
                    ),

                    'total_similarity' => round(
                        $committeeData['similarities'][$committeeId],
                        4
                    ),

                    // Diambil dari predictCommitteeScores()
                    'predicted_score' => round(
                        $predictedScores[$committeeId],
                        4
                    )

                ];
            }
        }

        return collect($details)
            ->sortByDesc('predicted_score')
            ->values()
            ->all();
    }

    // public function getCommitteeRecommendations($idUser)
    // {
    //     $keywords = $this->getRecommendedKeywords($idUser);

    //     $recommendations = [];

    //     $ratedCommittees = DB::table('tEvaluations')
    //         ->where('evaluator_id', $idUser)
    //         ->whereNotNull('target_committee')
    //         ->pluck('target_committee')
    //         ->toArray();

    //     $joinedCommittees = DB::table('tRegistrations')
    //         ->where('idUsers', $idUser)
    //         ->where('status', 'diterima')
    //         ->pluck('idCommittees')
    //         ->toArray();

    //     foreach ($keywords as $keywordId => $score) {

    //         $committees = DB::table('tListDivisionKeywords as lk')
    //             ->join('tListDivisions as ld', function ($join) {
    //                 $join->on('ld.idDivisions', '=', 'lk.idDivisions')
    //                     ->on('ld.idCommittees', '=', 'lk.idCommittees');
    //             })
    //             ->join('tCommittees as c', 'c.idCommittees', '=', 'ld.idCommittees')
    //             ->where('lk.idKeywords', $keywordId)
    //             ->where('c.is_active', 1)
    //             ->where('c.is_published', 1)
    //             ->whereNotIn('c.idCommittees', $ratedCommittees)
    //             ->whereNotIn('c.idCommittees', $joinedCommittees)
    //             ->select('c.idCommittees')
    //             ->distinct()
    //             ->get();

    //         foreach ($committees as $committee) {

    //             if (!isset($recommendations[$committee->idCommittees])) {
    //                 $recommendations[$committee->idCommittees] = 0;
    //             }

    //             $recommendations[$committee->idCommittees] += $score;
    //         }
    //     }

    //     arsort($recommendations);

    //     $topCommitteeIds = array_slice(
    //         array_keys($recommendations),
    //         0,
    //         3
    //     );

    //     $committees = DB::table('tCommittees')
    //         ->whereIn('idCommittees', $topCommitteeIds)
    //         ->get()
    //         ->keyBy('idCommittees');

    //     $result = collect();

    //     foreach ($topCommitteeIds as $id) {
    //         if(isset($committees[$id])){
    //             $result->push($committees[$id]);
    //         }
    //     }

    //     return $result;
    // }

    // public function predictRating($idUser, $idItem, $matrix, $similarities){
    //     $numerator = 0;
    //     $denominator = 0;

    //     foreach($similarities as $sim){
    //         if($sim['idUsers1'] == $idUser){
    //             $otherUser = $sim['idUsers2'];

    //             if(isset($matrix[$otherUser][$idItem])){
    //                 $numerator += $sim['similarity_score'] * $matrix[$otherUser][$idItem];
    //                 $denominator += abs($sim['similarity_score']);
    //             }
    //         }
    //     }

    //     if($denominator == 0) return 0;

    //     return $numerator / $denominator;
    // }

    // public function generateRecommendations($idUser){
    //     // hapus recommendation lama biar nga ke duplicate
    //     DB::table('tRecommendations')
    //         ->where('idUsers', $idUser)
    //         ->delete();
            
    //     $ratings = $this->getUserRatings();
    //     $matrix = $this->buildMatrix($ratings);
    //     $similarities = $this->calculateAllSimilarities($matrix);

    //     $items = DB::table('tCommittees')->pluck('idCommittees');

    //     $recommendations = [];

    //     foreach($items as $idItem){
    //         // klo uda perna nge rating di skip
    //         if(!isset($matrix[$idUser][$idItem])){
    //             $predicted = $this->predictRating($idUser, $idItem, $matrix, $similarities);

    //             // ambil yg score > 0
    //             if ($predicted > 0) {
    //                 $recommendations[] = [
    //                     'idUsers' => $idUser,
    //                     'idCommittees' => $idItem,
    //                     'predicted_score' => $predicted,
    //                 ];
    //             }
    //         }
    //     }

    //     // sort descending
    //     usort($recommendations, function ($a, $b) {
    //         return $b['predicted_score'] <=> $a['predicted_score'];
    //     });

    //     // TOP 3
    //     $topRecommendations = array_slice($recommendations, 0, 3);

    //     // insert final result
    //     if (!empty($topRecommendations)) {
    //         DB::table('tRecommendations')->insert($topRecommendations);
    //     }

    //     return $topRecommendations;
    // }
}