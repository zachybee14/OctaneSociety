<?php

use DateTime;

use OctaneSociety\Exceptions\ErrorMessageException;

use OctaneSociety\Models\Business;
use OctaneSociety\Models\Product;
use OctaneSociety\Models\Vehicle;
use OctaneSociety\Models\ArticleReview;

class ArticleReviews {
    public static function createReview($articleID, $review)
    {
        if (is_numeric($review['rating']) || !in_array($review['rating'], ['1','2','3', '4', '5']))
            throw new ErrorMessageException('Rating is a required field and it must be a number between 1 and 5');

        if (is_null($review['pros_body']))
            throw new ErrorMessageException('Pros is a required field');

        if (is_null($review['cons_body']))
            throw new ErrorMessageException('Cons is a required field');

        // if business
        if ($review['type'] == 'business') {
            $business = Business::find($review['target_id']);
            if (!$business)
                throw new ErrorMessageException('No business was found with ID ' . $review['target_id']);

            $targetID = $business->id;
        }
        else if ($review['type'] == 'product') {
            $product = Product::find($review['target_id']);
            if (!$product)
                throw new ErrorMessageException('No product was found with ID ' . $review['target_id']);

            $targetID = $product->id;
        }
        else if ($review['type'] == 'vehicle') {
            $vehicle = Vehicle::find($review['target_id']);
            if (!$vehicle)
                throw new ErrorMessageException('No vehicle was found with ID ' . $review['target_id']);

            $targetID = $vehicle->id;
        }
        else {
            throw new ErrorMessageException('Review type of ' . $review['type'] . ' is invalid.');
        }

        $review = new ArticleReview;
        $review->article_id = $articleID;
        $review->target_id = $targetID;
        $review->rating = $review['rating'];
        $review->pros = $review['pros_body'];
        $review->cons = $review['cons_body'];
        $review->created_at = new DateTime;
        $review->save();
    }

    public static function updateReview($articleID, $pros, $cons)
    {
        $review = ArticleReview::where('article_id', $articleID)->first();
        if (!$review)
            throw new ErrorMessageException('No review was found with article ID ' . $articleID);

        if (!is_null($pros))
            $review->pros = $pros;
        else if (!is_null($cons))
            $review->cons = $cons;
        else
            return;

        $review->updated_at = new DateTime;
        $review->save();
    }
}