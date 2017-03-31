<?php

use DateTime;

use OctaneSociety\Exceptions\ErrorMessageException;

use OctaneSociety\Models\Product;
use OctaneSociety\Models\ArticleSale;

class ArticleSales {

    public static function createSale($articleID, $content)
    {
        $target = $content['target'];
        if (is_null($target))
            throw new ErrorMessageException('A target is required.');

        if (!in_array($content['sale_type'], ['products', 'vehicle']))
            throw new ErrorMessageException('Sale type is required.');

        if (!in_array($content['condition'], ['new', 'low', 'medium', 'high']))
            throw new ErrorMessageException('Invalid product condition ' . $content['condition']);

        if ($target['type'] == 'product') {
            $product = Product::find($target['id']);
            if (!$product)
                throw new ErrorMessageException('No product was found with ID ' . $target['id']);

            $targetID = $product->id;
        }
        else if ($target['type'] == 'vehicle') {
            $vehicle = Vehicle::find($target['id']);
            if (!$vehicle)
                throw new ErrorMessageException('No vehicle found with ID ' . $target['id']);

            $targetID = $vehicle->id;
        }

        $sale = new ArticleSale;
        $sale->article_id = $articleID;
        $sale->target_id = $targetID;
        $sale->type = $target['type'];
        $sale->condition = $target['condition'];
        $sale->seller_price = $target['price'] ?: NULL;
        $sale->created_at = new DateTime;
        $sale->save();
    }

    public static function updateSale($articleID, $price)
    {
        $sale = ArticleSale::where('article_id', $articleID)->first();
        if (!$sale)
            throw new ErrorMessageException('No sale found with article ID ' . $articleID);

        if ($price)
            $sale->seller_price = $price;
        else
            return;

        $sale->save();
    }
}