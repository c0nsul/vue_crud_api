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
        //$skills = $employee->skills->toArray();
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
     * @return JsonResponse
     */
    public function edit(Employee $employee):JsonResponse
    {
        $skills = $employee->skills->toArray();
        if (count($skills) > 0) {
            foreach ($skills as $skill) {
                    $skillList[] = trim($skill['title']);
            }
            $employee->skill_list = implode(",", $skillList);
        }

        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Employee $employee
     * @param Skill $skillModel
     * @return JsonResponse
     */
    public function update(Request $request, Employee $employee, Skill $skillModel): JsonResponse
    {
        $updateEmployee = $employee->save($request->validate([
            'full_name' => 'required|string|min:3|max:255',
            'specialization' => 'string',
            'experience' => 'required|integer',
            'description' => 'string',
            'skill_list' => 'required|string',
        ]));

        if ($updateEmployee) {
            //delete
            $skillModel::where('employee_id',$request->id)->delete();
            //recreate
            $skills = explode(",", $request->skill_list);
            foreach ($skills as $skill) {
                $skillModel->create([
                    'employee_id' => $request->id,
                    'title' => trim($skill),
                ]);
            }

            return response()->json('ok');
        } else {
            return response()->json('update failed', 400);
        }
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
