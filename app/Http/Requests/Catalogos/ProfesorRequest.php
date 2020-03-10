<?php

namespace App\Http\Requests\Catalogos;

use App\Http\Requests\Request;
use Auth;

class ProfesorRequest extends Request {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return Auth::check() ? true : false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$id_object = $this->id;
		$rules = [
			'saldo_total' => 'required',
			'saldo_disponible' => 'required',
			'saldo_gasto' => 'required',
		];

		return $rules;
	}
	public function messages() {
		return [
			'saldo_total.required' => 'El campo Saldo Total es requerido!',
			'saldo_disponible.required' => 'El campo Saldo Disponible es requerido!',
			'saldo_gasto.required' => 'El campo Saldo Gasto es requerido!',
		];
	}
}