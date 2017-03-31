<?php namespace OctaneSociety\Http\Controllers;

use Auth;
use Input;
use Mail;
use Log;
use Response;
use View;
use Redirect;

use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Helpers\InputValidator;

use OctaneSociety\Models\Shop\Category;
use OctaneSociety\Models\Shop\Product;

class Shop extends Controller 
{
	public function showCategoryView($product) {
		$category = Category::where('key', $product)->first();
		
		return View::make('shop/categories', [
			'category' => $category->name
		]);
	}

	public function showProductView() {
		return View::make('shop/' . $categories . '/' . $product);
	}

	public function showCheckoutView() {
		return View::make('shop/checkout');
	}
}