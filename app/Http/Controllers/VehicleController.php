<?php namespace OctaneSociety\Http\Controllers;

use Input;
use Response;

use OctaneSociety\Models\VehicleMake;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Services\EdmundsDataService;

class VehicleController extends Controller {
	public function getMakes() {
		$makes = VehicleMake::select('name')->orderBy('name')->get();

		$makesList = [];
		foreach ($makes as $make) {
			$makesList[] = $make->name;
		}

		return Response::json([
			'success' => true,
			'makes' => $makesList
		]);
	}

	// this will also return the years for the models
	public function getModels() {
		$make = Input::get('make');

		$rawModelsData = EdmundsDataService::getModels($make);

		$models = [];
		foreach ($rawModelsData as $rawModel) {
			// create a list of years without out the edmunds ID
			$years = [];
			foreach ($rawModel->years as $rawYear)
				$years[] = $rawYear->year;

			// add the edntire model to the model list
			$models[] = [
				'name' => $rawModel->name,
				'years' => $years
			];
		}

		return Response::json([
			'success' => true,
			'models' => $models
		]);
	}

	public function getStyles() {
		$vehicle = Input::only('make', 'model', 'year');
		$rawStylesData = EdmundsDataService::getStyles([
			'make' => $vehicle['make'], 
			'model' => $vehicle['model'],
			'year' => $vehicle['year'],
			'style_id' => null
		]);

		$styles = [];
		foreach ($rawStylesData as $rawStyle) {
			$styles[] = [
				'style_id' => $rawStyle->id,
				'style_name' => $rawStyle->name
			];
		}

		return Response::json([
			'success' => true,
			'styles' => $styles
		]);
	}
}