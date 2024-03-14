<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    function getUsersTotalReviews() {
        return $query = DB::table('users')
            ->select([
                'users.id AS user_id',
                'users.name AS user_name',
                DB::raw('COUNT(reviews.id) AS total_reviews'),
                DB::raw('SUM(CASE WHEN reviews.status = 1 THEN 1 ELSE 0 END) AS total_approved_reviews'),
                DB::raw('
                    SUM(CASE WHEN reviews.status = 1 THEN 1 ELSE 0 END) AS total_approved_reviews,
                    (
                        SELECT COUNT(DISTINCT article_id) FROM 
                        (
                            SELECT 
                            a.id AS article_id,
                            a.title AS article_title,
                            COUNT(r.id) AS total_reviews,
                            SUM(r.status = 1) AS total_approved_reviews
                            FROM 
                                articles a
                            LEFT JOIN 
                                reviews r ON a.id = r.article_id
                            GROUP BY 
                                a.id, a.title
                            HAVING 
                                COUNT(r.id) = SUM(r.status = 1)
                        ) as approved_aticles 
                        WHERE user_id = users.id
                    ) AS total_published_articles
                '),
            ])
            ->leftJoin('reviews', 'users.id', '=', 'reviews.user_id')
            ->groupBy(['users.id', 'users.name'])
            ->get();
    }
}
