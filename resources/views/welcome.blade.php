<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link class="rounded" rel="icon" type="image/svg+xml" href="{{ asset('if.svg') }}">

    @fonts

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .polo-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 935px;
            min-height: 580px;
            background: #ffffff;
            border: 1px solid #dbdbdb;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        /* Caminho da imagem que pode substituir na pasta public/images */
        /* .polo-left {            
            background-image: url('{{ asset("images/poloIfce.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            color: #ffffff;
            flex: 1.1;
        } */
        /* Degradê atualizado com tons de verde do Polo */
        .polo-left {
            background: linear-gradient(135deg, #054f2c 0%, #0d6e3d 25%, #13834b 50%, #1bad63 75%, #2ad17c 100%);
            padding: 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            color: #ffffff;
            flex: 1.1;
        }

        .polo-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0,0,0,0) 70%, rgba(255,255,255,0.08) 100%);
            pointer-events: none;
        }

        .polo-right {
            flex: 0.9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #ffffff;
        }

        .form-box {
            width: 100%;
            max-width: 350px;
        }

        .polo-input {
            width: 100%;
            padding: 11px 8px;
            font-size: 12px;
            background: #fafafa;
            border: 1px solid #dbdbdb;
            border-radius: 4px;
            margin-bottom: 8px;
            outline: none;
            color: #262626;
            transition: border-color 0.2s ease;
        }

        .polo-input:focus {
            border-color: #1bad63;
            background: #ffffff;
        }

        /* Botão de Entrar Estilo pologram */
        .polo-btn {
            width: 100%;
            background-color: #1bad63;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 7px 0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 0.2s ease;
        }

        .polo-btn:hover {
            background-color: #13834b;
        }

        .polo-divider {
            display: flex;
            align-items: center;
            margin: 18px 0;
            width: 100%;
        }

        .polo-line {
            flex: 1;
            height: 1px;
            background: #dbdbdb;
        }        
        /* Garante que em telas menores o empilhamento fique bonito e centralizado */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .polo-container { 
                flex-direction: column; 
                min-height: auto;
                box-shadow: none;
                border: none;
                background: transparent;
            }
            .polo-left { 
                min-height: 220px; 
                justify-content: center; 
                text-align: center; 
                gap: 15px;
                border-radius: 8px 8px 0 0;
                padding: 30px 20px;
            }
            .polo-right { 
                background: transparent; 
                padding: 25px 0; 
            }
        }
    </style>
</head>

<body>

<div class="polo-container">
    
    <!-- COLUNA ESQUERDA: Degradê Verde Polo, Título e Subtítulo -->
    <div class="polo-left">
        <div style="display: flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; font-size: 14px;">
            <img src="{{ asset('if.svg') }}" alt="Logo" style="width: 26px; height: 26px; filter: brightness(0) invert(1);" onerror="this.style.display='none'">
            <span>{{ config('app.name', 'Laravel') }}</span>
        </div>

        <div style="margin: auto 0;">
            <h1 style="font-size: 34px; font-weight: 800; line-height: 1.25; margin-bottom: 12px; letter-spacing: -0.5px;">
                Serviços de Monitoramento
            </h1>
            <p style="font-size: 14px; color: rgba(255,255,255,0.9); line-height: 1.45; font-weight: 400;">
                Gerenciador de backups e conexões de internet.
            </p>
        </div>
        
        <div style="font-size: 11px; color: rgba(255,255,255,0.6);">
            v{{ app()->version() }} &copy; {{ date('Y') }}
        </div>
    </div>

    <!-- COLUNA DIREITA: Campos do Formulário -->
    <div class="polo-right">
        <div class="form-box">
                        
            <!-- Bloco do Formulário -->
            <div style="background: #ffffff; border: 1px solid #dbdbdb; padding: 30px 25px; border-radius: 8px;">
                <!-- <h1 style="font-size: 14px; color: #1bad63; line-height: 1.45; font-weight: 500; margin-botton: 20;">Login:</h1> -->
                    <div class="polo-divider">
                    <div class="polo-line"></div>
                    <span style="font-size: 11px; color: #8e8e8e; font-weight: 600; margin: 0 10px; text-transform: uppercase;">Login</span>
                    <div class="polo-line"></div>
                </div>

                @if (session('status'))
                    <div style="color: #2fb344; font-size: 13px; margin-bottom: 15px; text-align: center; font-weight: 500;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Telefone, nome de usuário ou email" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="polo-input"
                    >
                    @if($errors->get('email'))
                        <span style="color: #ed4956; font-size: 12px; display: block; margin-bottom: 10px;">{{ $errors->first('email') }}</span>
                    @endif

                    <input 
                        id="password" 
                        type="password" 
                        name="password"
                        placeholder="Senha" 
                        required 
                        autocomplete="current-password"
                        class="polo-input"
                    >
                    @if($errors->get('password'))
                        <span style="color: #ed4956; font-size: 12px; display: block; margin-bottom: 10px;">{{ $errors->first('password') }}</span>
                    @endif

                    <div style="display: flex; align-items: center; margin: 14px 0 12px 0;">
                        <label style="display: inline-flex; align-items: center; cursor: pointer; font-size: 12px; color: #8e8e8e;">
                            <input id="remember_me" type="checkbox" name="remember" style="margin-right: 8px; width: 14px; height: 14px; accent-color: #1bad63;">
                            Salvar informações de login
                        </label>
                    </div>

                    <button type="submit" class="polo-btn">
                        Entrar
                    </button>
                </form>
            </div>

            <!-- Bloco de Links e Cadastro -->
            <div style="background: #ffffff; border: 1px solid #dbdbdb; padding: 20px; border-radius: 8px; margin-top: 10px; text-align: center; font-size: 14px; color: #262626;">
                @if (Route::has('register'))
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" style="color: #1bad63; font-weight: 600; text-decoration: none;">
                        Cadastre-se
                    </a>
                @endif

                <div class="polo-divider">
                    <div class="polo-line"></div>
                    <span style="font-size: 11px; color: #8e8e8e; font-weight: 600; margin: 0 10px; text-transform: uppercase;">Ou</span>
                    <div class="polo-line"></div>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: #054f2c; font-size: 12px; text-decoration: none; font-weight: 500;">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
</body>