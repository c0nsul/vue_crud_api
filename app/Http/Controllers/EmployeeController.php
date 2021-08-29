<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $employees = Employee::all();
        //$employees = Employee::find('')->skills;
        //$employees->skills = Employee::with('skills')->get();
        //dd($employees);
        return response([
                'timestamp' => time(),
                'data' => $employees,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @param Skill $skillModel
     * @return JsonResponse
     */
    public function store(Request $request, Skill $skillModel)
    {
        $newEmployee = Employee::create($request->validate([
            'full_name' => 'required|string|min:3|max:255',
            'specialization' => 'string',
            'experience' => 'required|integer',
            'description' => 'string',
            'skills' => 'required|string',
        ]));

        if ($newEmployee) {
            //create skills
            $skills = explode(",", $request->skills);
            foreach ($skills as $skill) {
                $skillModel->create([
                    'employee_id' => $newEmployee->id,
                    'title' => $skill,
                ]);
            }

            return response()->json('ok');
        } else {
            return response()->json('created failed', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Employee $employee
     * @return Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Employee $employee
     * @return Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
