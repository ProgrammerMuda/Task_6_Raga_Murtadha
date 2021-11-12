<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller{
    
    function index() {
        $response = Student::all();
        $data = ["message" => "find all Student", "data" => $response];
        return response()->json($response, 200);
    }

    function getById($id){

      $student = Student::find($id);
      if ($student) {
      $data = ["message" => "find students with Id", "data" => $student];
      return response()->json($data, 200);
      }else{
          return response()->json(["message" => "error", "info" => "Data tidak ditemukan"], 404);
      }

    }

    function create(Request $request){
        $nama = $request->nama;
        $nim = $request->nim;
        $email = $request->email;
        $jurusan = $request->jurusan;
       try {
            if (Student::where('nim', $nim)->first()) {
                return response()->json(["message" => "error", "info" => "NIM " . $nim . " already registered"], 200);
            }
            if (Student::where('email', $email)->first()) {
                return response()->json(["message" => "error", "info" => "Email " . $email . " already registered"], 200);
            }
            $student = Student::create([
                "nama" => $nama,
                "nim" => $nim,
                "email" => $email,
                "jurusan" => $jurusan,
            ]);
            $data = [
                "message" => "student is created successfully",
                "data" => $student
            ];
            return response()->json($data, 201);
        } catch (Exception $e) {
            return response()->json(["message" => "error", "info" => $e->errorInfo[2]], 500);
        }
    }

    function update(Request $request, $id){
        $nama = $request->nama;
        $nim = $request->nim;
        $email = $request->email;
        $jurusan = $request->jurusan;
        try {
            $student = Student::find($id);
            if ($student) {
                if ($student->nim != $nim && Student::where('nim', $nim)->first()) {
                    return response()->json(["message" => "error", "info" => "NIM " . $nim . " already registered"], 200);
                }
                if ($student->email != $email && Student::where('email', $email)->first()) {
                    return response()->json(["message" => "error", "info" => "Email " . $email . " already registered"], 200);
                }
                $student->update([
                    'nama' => ($nama != null) ? $nama : $student->nama,
                    'nim' => ($nim != null) ? $nim : $student->nim,
                    'email' => ($email != null) ? $email : $student->email,
                    'jurusan' => ($jurusan != null) ? $jurusan : $student->jurusan
                ]);

                $data = ["message" => "student is updated successfully", "data" => $student];

                return response()->json($data, 200);
            } else {
                return response()->json(["message" => "error", "info" => "Student not found, id : " . $id], 404);
            }
        } catch (Exception $e) {
            return response()->json(["message" => "error", "info" => $e->errorInfo[2]], 500);
        }
    }

 
    function destroy($id) {

      $student = Student::find($id);

        if ($student) {
                $student->delete();

            $data = [
                    'message' => 'Student is deleted'
            ];

             return response()->json($data, 200);
        }
        else {
            $data = [
                'message' => 'Data not found'
            ];

            return response()->json($data, 404);
        }
    }

}
