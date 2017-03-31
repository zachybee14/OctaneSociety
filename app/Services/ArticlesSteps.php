<?php
/**
 * Created by PhpStorm.
 * User: zach
 * Date: 10/3/16
 * Time: 3:45 PM
 */

use DateTime;

use OctaneSociety\Exceptions\ErrorMessageException;

use OctaneSociety\Models\Product;
use OctaneSociety\Models\ArticleStep;

class ArticleSteps {

    public static function createStep($articleID, $steps)
    {
        if (count($steps) < 2)
            throw new ErrorMessageException('There must be at least two steps in a how to article.');

        $stepCount = 1;
        foreach ($steps as $step) {
            if (is_null($step['title']))
                throw new ErrorMessageException('Step title is required.');

            if (is_null($step['body']))
                throw new ErrorMessageException('Step body is required.');

            $articleStep = new ArticleStep;
            $articleStep->article_id = $articleID;
            $articleStep->step_number = is_numeric($step['number']) ?: $stepCount;
            $articleStep->title = $step['title'];
            $articleStep->body = $step['body'];
            $articleStep->created_at = new DateTime;
            $articleStep->save();

            if (count($step['products']) > 0) {
                $products = $step['products'];
                foreach ($products as $product) {
                    $productObject = Product::where('id', $product['id'])->orWhere('name', $product['name']);
                    if (!$productObject)
                        throw new ErrorMessageException('No product was found with the ID ' . $product['id'] . ' or name ' . $product['name']);

                    $step->products()->attach($productObject);
                }
            }

            if (count($step['tools']) > 0) {
                foreach ($step['tools'] as $tool) {
                    $toolObject = Tool::find($tool['id']);
                    if (!$toolObject)
                        throw new ErrorMessageException('No tool was found with the ID ' . $tool['id']);

                    $step->tools()->attach($toolObject);
                }
            }

            $stepCount++;
        }
    }

    public static function updateStep($articleID, $step)
    {
        $step = ArticleStep::where('article_id', $articleID)->where('step_number', $step['number'])->first();
        if (!$step)
            throw new ErrorMessageException('No step was found with article ID ' . $articleID);

        if (!is_null($step['title'])) {
            $step->title = $step['title'];
        }
        else if (!is_null($step['body'])) {
            $step->body = $step['body'];
        }
        else if (!is_null($step['products'])) {
            $step->load('products');

            foreach ($step['products'] as $product) {
                $productObject = Product::find($product['id']);
                if (!$productObject)
                    throw new ErrorMessageException('No product was found with ID ' . $product['id']);

                $stepProduct = $step->products()->find($productObject->id);
                if ($stepProduct)
                    continue;

                $step->products()->attach($productObject);
            }
        }
        else if (!is_null($step['tools'])) {
            $step->load('tools');

            foreach ($step['tools'] as $tool) {
                $toolObject = Tool::find($tool['id']);
                if (!$toolObject)
                    throw new ErrorMessageException('No product was found with ID ' . $tool['id']);

                $stepTool = $step->tools()->find($toolObject->id);
                if ($stepTool)
                    continue;

                $step->tools()->attach($toolObject);
            }
        }
        else {
            return;
        }

        $step->updated_at = new DateTime;
        $step->save();
    }
}
