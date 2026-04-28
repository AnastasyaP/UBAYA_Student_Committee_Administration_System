<?php

namespace App\Services;

class UBCFService
{
    public function test()
    {
        return "Service jalan!";
    }

     public function getUserRatings(){
        $ratings = DB::table('tEvaluations')
                ->whereNotNull('target_committee')
                ->select([
                    'evaluator_id as idUser',
                    'target_committee as committee',
                    'ratings'
                ])
                ->get();

        return $ratings;
    }

    public function buildMatrix($ratings){
        $matrix = [];

        foreach($ratings as $r){
            $matrix[$r->idUser][$r->committee] = $r->ratings;
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
        $similarities = [];

        foreach($matrix as $user1 => $ratings1){
            foreach($matrix as $user2 => $ratings2){
                if($user1 == $user2) continue;

                $sim = $this->pearsonSimilarity($ratings1, $ratings2);


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

    public function predictRating($idUser, $idItem, $matrix, $similarities){
        $numerator = 0;
        $denominator = 0;

        foreach($similarities as $sim){
            if($sim['idUsers1'] == $idUser){
                $otherUser = $sim['idUsers2'];

                if(isset($matrix[$otherUser][$idItem])){
                    $numerator += $sim['similarity_score'] * $matrix[$otherUser][$idItem];
                    $denominator += abs($sim['similarity_score']);
                }
            }
        }

        if($denominator == 0) return 0;

        return $numerator / $denominator;
    }

    public function generateRecommendations($idUser){
        $ratings = $this->getUserRatings();
        $matrix = $this->buildMatrix($ratings);
        $similarities = $this->calculateAllSimilarities($matrix);

        $items = DB::table('tCommittees')->pluck('idCommittees');

        $recommendations = [];

        foreach($items as $idItem){
            if(!isset($matrix[$idUser][$idItem])){
                $predicted = $this->predictRating($idUser, $idItem, $matrix, $similarities);

                $recommendations[] = [
                    'idUsers' => $idUser,
                    'idCommittees' => $idItem,
                    'predicted_score' => $predicted,
                ];
            }
        }

        DB::table('tRecommendations')->insert($recommendations);
    }
}