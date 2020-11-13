<?php

namespace App\Http\Controllers\Api;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserLogs;

class BankAccountController extends Controller
{

    use UserLogs;

    public function store(Request $request)
    {
    	$request->validate([
    		'bank_name' => 'required|max:45',
    		'first_name' => 'required|max:20',
            'middle_initial' => 'required',
    		'last_name' => 'required|max:20',
    		'account_number' => 'required|max:20'
    	]);

        $count_bank_account = BankAccount::count();

        $bank_account = new BankAccount;

        $bank_name = filter_var($request->bank_name ,FILTER_SANITIZE_STRING);
        $first_name = filter_var($request->first_name ,FILTER_SANITIZE_STRING);
        $middle_initial = str_replace('.', "", $request->middle_initial);
        $last_name = filter_var($request->last_name ,FILTER_SANITIZE_STRING);
        $account_number = filter_var($request->account_number ,FILTER_SANITIZE_STRING);

        $bank_account->bank_name = ucwords($bank_name);
        $bank_account->first_name = ucwords(strtolower($first_name));
        $bank_account->middle_initial = strtoupper($middle_initial).'.';
        $bank_account->last_name = ucwords(strtolower($last_name));
        $bank_account->number = $account_number;

        if ($count_bank_account == 0)
        {
            $bank_account->active = 1;
        }

        $bank_account->save();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add new bank account. ID: '.$bank_account->id.', Account Number: '.$bank_account->number.','
        ];

        $this->createUserLog($array_params);

    	return response()->json(['success'=>true]);

    }

    public function update(Request $request, BankAccount $bank_account)
    {
    	$request->validate([
    		'bank_name' => 'required|max:45',
    		'first_name' => 'required|max:20',
            'middle_initial' => 'required',
    		'last_name' => 'required|max:20',
    		'account_number' => 'required|max:20'
    	]);

        $bank_name = filter_var($request->bank_name ,FILTER_SANITIZE_STRING);
        $first_name = filter_var($request->first_name ,FILTER_SANITIZE_STRING);
        $middle_initial = str_replace('.', "", $request->middle_initial);
        $last_name = filter_var($request->last_name ,FILTER_SANITIZE_STRING);
        $account_number = filter_var($request->account_number ,FILTER_SANITIZE_STRING);

    	$bank_account->bank_name = ucwords($bank_name);
    	$bank_account->first_name = ucwords($first_name);
        $bank_account->middle_initial = strtoupper($middle_initial).'.';
    	$bank_account->last_name = ucwords($last_name);
    	$bank_account->number = $account_number;
    	$bank_account->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit bank account. ID: '.$bank_account->id.', Account Number: '.$bank_account->number.','
        ];

        $this->createUserLog($array_params);

    	return response()->json(['success' => true]);
    }

    public function deleteBankAccount(BankAccount $bank_account, $admin)
    {
        $account_number = $bank_account->number;
        $bank_account_id = $bank_account->id;

        if ($bank_account->delete())
        {
            $array_params = [
                'id' => $admin,
                'action' => 'Edit bank account. ID: '.$bank_account_id.', Account Number: '.$account_number.','
            ];

            $this->createUserLog($array_params);

            return response()->json(['success'=>true]);
        }


    }

    public function get()
    {
    	$bank_accounts = BankAccount::orderBy('id')->get();

    	return response()->json($bank_accounts);	
    }

    public function setAsDefault(BankAccount $bankAccount)
    {
        $has_default = BankAccount::where('active', 1)->count();

        if ($has_default > 0)
        {
            // it has been default bank account
            // update

            $bank_account = BankAccount::all();

            foreach ($bank_account as $b_account) {
                $b_account->active = 0;
                $b_account->update();
            }

            $bankAccount->active = 1;
            $bankAccount->update();

            $response = ['success'=>true];
        }
        else
        {
            // set a default bank account
            $bankAccount->active = 1;
            $bankAccount->update();

            $response = ['success'=>true];
        }

        return response()->json($response);
    }
}
