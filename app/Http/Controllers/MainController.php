<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function generateExercises(Request $request): View
    {
        $request->validate([
            //checkboxes
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_sum,check_subtraction,check_division',
            'check_division' => 'required_without_all:check_sum,check_subtraction,check_multiplication',

            //parcelas
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',

            //numero de exercicios
            'number_exercises' => 'required|integer|min:5|max:50'
        ]);

        $operations = [];
        if($request->check_sum) { $operations[] = 'sum'; }
        if($request->check_subtraction) { $operations[] = 'subtraction';  }
        if($request->check_multiplication) { $operations[] = 'multiplication'; }
        if($request->check_division) { $operations[] = 'division'; }

        //pegar number (min e max)
        $min = $request->number_one;
        $max = $request->number_two;

        //pegar number of exercises
        $numberExercises = $request->number_exercises;

        //gerar exercicios
        $exercises = [];
        for ($i = 1; $i <= $numberExercises; $i++) {
          $exercises[] = $this->generateExercise($i, $operations, $min, $max);
        }

        //guardar exercicios em sesssão
        session(['exercises' => $exercises]);


        return view('operations', ['exercises' => $exercises]);
    }

    public function printExercises()
    {
       //verificar se tem exercicios na sessão
       if(!session()->has('exercises')) {
        return redirect()->route('home');
       }

       $exercises = session()->get('exercises');
       echo '<pre>';
       echo '<h1>Exercícios de Matemática</h1>';
       foreach($exercises as $exercise) {
           echo '<h2><small>' . $exercise['exercise_number'] . ') </small> ' . $exercise['exercise'] . '</h2>';
       }

       //soluções
       echo '<hr>';
       echo '<h1>Soluções</h1><br>';
       foreach($exercises as $exercise) {
        echo '<h2><small>' . $exercise['exercise_number']. ') </small> ' . $exercise['solution'] . '</h2>';
       }
    }

    public function exportExercises()
    {
        //verificar se tem exercicios na sessão
        if(!session()->has('exercises')) {
        return redirect()->route('home');
       }

       $exercises = session()->get('exercises');

       //criar arquivo para exportar
       $filename = 'exercicios_'.env('APP_NAME').'_'.date('Ymdhis').'.txt';

       $content = "Exercícios de Matemática (".env('APP_NAME').") \n\n";
       foreach($exercises as $exercise) {
        $content .= $exercise['exercise_number'] . ') ' . $exercise['exercise'] . "\n";
       }

       //adicionar soluções
       $content .= "\n" . str_repeat('-', 50) . "\n";
       $content .= "Soluções \n".str_repeat('-', 50)."\n\n";
       foreach($exercises as $exercise) {
        $content .= $exercise['exercise_number'] . ') ' . $exercise['solution'] . "\n";
       }

       //exportar arquivo
       //Storage::disk('public')->put($filename, $content);

       return response($content)
                        ->header('Content-Type', 'text/plain')
                        ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    private function generateExercise($i, $operations, $min, $max){
        $operation = $operations[array_rand($operations)];
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        $exercise = "";
        $solution = "";

        switch ($operation) {
            case 'sum':
                $exercise = "$number1 + $number2 =";
                $solution = $number1 + $number2;
                break;
            case 'subtraction':
                $exercise = "$number1 - $number2 =";
                $solution = $number1 - $number2;
                break;
            case 'multiplication':
                $exercise = "$number1 x $number2 =";
                $solution = $number1 * $number2;
                break;
            case 'division':
                
                //divisão por zero
                if($number2 == 0) {  $number2 = 1; }
                
                $exercise = "$number1 ÷ $number2 =";
                $solution = $number1 / $number2;
                break;
        };

        //se a solução do exercicio for um numero decimal, arredondar para 2 casas decimais
        if(is_float($solution)) {
            $solution = round($solution, 2);
        }

        return [
            'operation' => $operation,
            'exercise_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'exercise' => $exercise,
            'solution' => "$exercise $solution"
        ];
    }

}
