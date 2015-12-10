<?php namespace OctaneSociety\Helpers;

use Program\Plan;
use User;
use User\Payment;

class BillingHelper {
	public static function updateNextPaymentPivot($user, $program, $plan = null) {
		// if the program doesn't have a pivot, get it again with the pivot
		if (!isset($program->pivot))
			$program = $user->programs()->find($program->id);

		// get the plan if we weren't passed it
		if (is_null($plan)) {
			$planId = $user->programs()->find($program->id)->pivot->plan_id;
			if (is_null($planId))
				return null;
			$plan = Plan::find($planId);
		}

		// get the user's payments towards the program
		$payments = Payment::where('user_id', $pivot->user_id)->where('program_id', $pivot->program_id)->where('status', 'ok')->orderBy('created_at')->get(['created_at', 'amount']);

		// add up the total amount paid
		$amountPaid = 0;
		foreach ($payments as $payment)
			$amountPaid += $amount;

		// the first payment should have come the day of the signup
		$paymentDate = date('Y-m-d', strtotime($program->pivot->signup_at));

		// but if there was a trial period, offset the start date
		if ($plan->trial_duration > 0) {
			$paymentDate = strtotime('+' . $plan->trial_duration . ' days', strtotime($paymentDate));
		}

		// if we haven't yet reached the start date, then that's our next payment
		if (strtotime($paymentDate) < strtotime(date('Y-m-d'))) {
			// so set it
			$program->programs()->updateExistingPivot($program->id, [
				'next_payment_date' => $paymentDate,
				'next_payment_amount' => $plan->initial_cost
			]);

			// then bail
			return;
		}

		// otherwise, we should have paid that
		$shouldHavePaid = $plan->initial_cost;

		// infinite loop! :)
		while (true) {
			// switch between the recurring frequency to determine when the next payment date should be / have been
			switch($plan->recurring_frequency) {
				case null:
					$nextPaymentDate = date('Y-m-d');
					break;

				case 'daily':
					$nextPaymentDate = date('Y-m-d', strtotime('+1 day', strtotime($paymentDate)));
					break;

				case 'weekly':
					$nextPaymentDate = date('Y-m-d', strtotime('+1 week', strtotime($paymentDate)));
					break;

				case 'biweekly':
					$nextPaymentDate = date('Y-m-d', strtotime('+2 weeks', strtotime($paymentDate)));
					break;

				case 'monthly':
					$nextPaymentDate = date('Y-m-d', strtotime('+1 month', strtotime($paymentDate)));
					break;

				default:
					throw new \Exception('Invalid plan recurring frequency');
			}
		}

		// if we have the initial plus all the recurrings
		if (count($payments) >= $plan->recurring_duration + 1) {
			// we're done and can remove the next payment date
			DB::table('user_program')->where('user_id', $user->id)->where('program_id', $program->id)->update([
				'next_payment_date' => null
			]);

			// and then move onto the next pivot
			continue;
		}

		

		// set the next payment date
		DB::table('user_program')->where('user_id', $user->id)->where('program_id', $program->id)->update([
			'next_payment_date' => $nextPaymentDate
		]);
	}
}