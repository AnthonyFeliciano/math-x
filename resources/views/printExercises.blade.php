<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>
        @media print {
            @page {
                margin: 2cm;
            }

            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: black;
            color: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #8257e6;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 15px;
        }
        .exercise-container {
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .exercise {
            font-size: 18px;
            margin: 15px 0;
            background: #1e1e1e;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #8257e6;
        }
        .exercise strong {
            background: #8257e6;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .solutions {
            page-break-before: always;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #8257e6;
            font-size: 12px;
            color: #a0a0a0;
        }

        .answer-line {
            display: inline-block;
            width: 100px;
            border-bottom: 1px solid #8257e6;
            margin-left: 10px;
        }
        h1, h2 {
            color: #8257e6;
        }
    </style>
</head>
<body>

    <div class="d-flex justify-content-end no-print">
        <a href="{{ route('home') }}">
            <button class="btn btn-secondary px-5">Voltar</button>
        </a>
        <button onclick="window.print()" class="btn btn-secondary px-5">Imprimir</button>
    </div>

    
    <div class="header">
        <div class="text-center my-3">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="logo" class="img-fluid" width="250px">
        </div>
        <h1>Exercícios de Matemática</h1>
        <p>Data: {{ date('d/m/Y') }}</p>
    </div>

    <div class="exercise-container">
        @foreach($exercises as $exercise)
            <div class="exercise">
                <strong>{{ $exercise['exercise_number'] }}</strong> 
                {{ $exercise['exercise'] }}
                <span class="answer-line"></span>
            </div>
        @endforeach
    </div>
    
    <div class="solutions">
        <div class="header">
            <h2>Soluções</h2>
        </div>
        
        @foreach($exercises as $exercise)
            <div class="exercise">
                <strong>{{ $exercise['exercise_number'] }}</strong> 
                {{ $exercise['solution'] }}
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} - Todos os direitos reservados &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>