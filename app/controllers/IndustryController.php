<?php

class IndustryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// return 'You are in Industrys.index';
		$Industrys = Industry::orderBy('industry')->get();
		return View::make('Industrys.index', compact('Industrys'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		return View::make('Industrys.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$input = Input::all(); // except('xxx'); // only('xxx');

		$validation = Validator::make($input, Industry::$rules);

		if ($validation->passes())
		{
			Industry::create($input);
			return Redirect::route('Industrys.index');
		}
		return Redirect::route('Industrys.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$Industry = Industry::find($id);
		if (is_null($Industry))
		{
			return Redirect::route('Industrys.index');
		}
		return View::make('Industrys.show', compact('Industry'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		$Industry = Industry::find($id);
		if (is_null($Industry))
		{
			return Redirect::route('Industrys.index');
		}
		return View::make('Industrys.edit', compact('Industry'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		$input = Input::all();

		$validation = Validator::make($input, Industry::$rules);

		if ($validation->passes())
		{
			$Industry = Industry::find($id);
			$Industry->update($input);
			return Redirect::route('Industrys.show', $id);
		}
		return Redirect::route('Industrys.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		Industry::find($id)->delete();
		return Redirect::route('Industrys.index');
	}


}
