<?php namespace OctaneSociety\Http\Controllers;

use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use OctaneSociety\Exceptions\ErrorMessageException;
use OctaneSociety\Helpers\ResultFormatter;
use Request;
use Response;

use OctaneSociety\Models\Vehicle\Make;
use OctaneSociety\Models\VehicleSignUpLog;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use OctaneSociety\Helpers\InputValidator;
use OctaneSociety\Services\EdmundsData;

class Vehicles extends Controller {
	public function getMakes() {
        $year = request('year');

        if (!preg_match('/^[0-9]{4}$/', $year))
            throw new BadRequestHttpException('Invalid year format');

        $makes = EdmundsData::getMakes($year);

		return Response::json([
			'success' => true,
			'makes' => ResultFormatter::format($makes, [
                'niceName:id', 'name',
                'models' => [ 'niceName:id', 'name' ]
            ])
		]);
	}

	public function getStyles() {
		$input = request()->only('make', 'model', 'year');

        if (!preg_match('/^[0-9]{4}$/', $input['year']))
            throw new BadRequestHttpException('Invalid year format');
        if (!preg_match('/^[a-z0-9-]+$/', $input['make']))
            throw new BadRequestHttpException('Invalid make format');
        if (!preg_match('/^[a-z0-9-]+$/', $input['model']))
            throw new BadRequestHttpException('Invalid model format');

		$rawStylesData = EdmundsData::getStyles($input['year'], $input['make'], $input['model']);

		$styles = [];
		foreach ($rawStylesData as $rawStyle) {
			$styles[] = [
				'id' => $rawStyle->id,
				'name' => $rawStyle->name
			];
		}

		return Response::json([
			'success' => true,
			'styles' => ResultFormatter::format($styles, [ 'id', 'name' ])
		]);
	}

	public function logVehicle() {
	    $input = request()->only('year', 'make', 'model', 'style_id');

        $sessionId = Request::session()->getId();

        $logEntry = VehicleSignUpLog::where('session_id', $sessionId)->first();
        if (!$logEntry) {
            $logEntry = new VehicleSignUpLog;
            $logEntry->session_id = $sessionId;
        }

        if (!$input['style_id']) {
            $logEntry->year = $input['year'];
            $logEntry->make = $input['make'];
            $logEntry->model = $input['model'];

            if ($logEntry->edmunds_style_id) {
                $logEntry->style = null;
                $logEntry->edmunds_style_id = null;
                $logEntry->vehicle_base_cost = null;
                $logEntry->engine_cylinders = null;
                $logEntry->engine_hp = null;
                $logEntry->engine_torque = null;
                $logEntry->engine_ml = null;
                $logEntry->transmission_type = null;
            }
        }

        elseif ($input['style_id'] != $logEntry->edmunds_style_id) {
            $styleDetail = EdmundsData::getStyle($input['style_id']);

            $logEntry->year = $styleDetail->year->year;
            $logEntry->make = $styleDetail->make->name;
            $logEntry->model = $styleDetail->model->name;
            $logEntry->style = $styleDetail->name;
            $logEntry->edmunds_style_id = $styleDetail->id;
            $logEntry->vehicle_base_cost = $styleDetail->price->baseMSRP ?? null;
            $logEntry->engine_cylinders = $styleDetail->engine->cylinder ?? null;
            $logEntry->engine_hp = $styleDetail->engine->horsepower ?? null;
            $logEntry->engine_torque = $styleDetail->engine->torque ?? null;
            $logEntry->engine_ml = $styleDetail->engine->displacement ?? null;
            $logEntry->transmission_type = $styleDetail->transmission->transmissionType ?? null;
        }

        $logEntry->save();

        return response(null, 200);
    }
}