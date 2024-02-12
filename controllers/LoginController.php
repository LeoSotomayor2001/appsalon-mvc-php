<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas=[];
        $auth=new Usuario();

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);

            $alertas=$auth->validarLogin();

            if(empty($alertas)){
                //Comprobar que exista el usuario
                $usuario=Usuario::where('email',$auth->email);
                if($usuario){
                    //verificar password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autenticar el usuario
                        if(!isset($_SESSION)) {
                            session_start();
                            $_SESSION=[];
                        }

                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre . " ". $usuario->apellido;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //redireccionamiento
                        if($usuario->admin==='1'){
                            $_SESSION['admin']= $usuario->admin ?? null;
                            header('Location: /admin');
                        }
                        else{
                           header('Location: /cita');
                        }

                        
                    }
                }
                else{
                    Usuario::setAlerta('error','El usuario no existe');
                }
            }
            
        }
        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);

    }
    public static function logout(){
        session_start();

        $_SESSION=[];

        header('Location: /');
    }
    public static function olvide(Router $router){
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarEmail();

            if(empty($alertas)){
                $usuario=Usuario::where('email',$auth->email);
                
                if($usuario && $usuario->confirmado==="1"){
                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email
                    $email=new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    //alerta de exito
                    Usuario::setAlerta('exito','Revisa tu email');
                }
                else{
                    Usuario::setAlerta('error','El email no existe o no esta confirmado');
                    
                }
            }
        }
        $alertas=Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas' =>$alertas
        ]);
    }
    public static function recuperar(Router $router){
        
        $alertas=[];
        $error=false;
        // Obtenemos el token del usuario desde get.
         $token = s($_GET["token"] ?? "");
 
        // Si token no obtiene un valor desde GET detenemos la renderización de la vista.
        if(!$token) {
            Usuario::setAlerta("error", "no hay token");
            $error = true;
        
        }
        $usuario=Usuario::where('token',$token);
        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido");
            $error=true;
        }
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Leer el nuevo password y guardarlo
            $password=new Usuario($_POST);
            $alertas=$password->validarPassword();

            if(empty($alertas)){
                $usuario->password=$password->password;
                $usuario->hashPassword();
                $usuario->token="";
                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' =>$alertas,
            'error' =>$error
        ]);
    }
    public static function crear(Router $router){
        $alertas=[];
        $usuario= new Usuario;
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNewCuenta();
            
            //revisar que alertas este vacio    
            if(empty($alertas)){
                //verificar que el usuario no este registrado
                $resultado=$usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas=Usuario::getAlertas();
                }
                else{
                    //hashear el password
                    $usuario->hashPassword();

                    //Generar un token unico
                    $usuario->crearToken();

                    $email=new Email($usuario->email,$usuario->nombre,$usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado=$usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }

                }
            }
        }

        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function mensaje (Router $router){
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router){
        $alertas=[];
        $token=s($_GET['token']);
        $usuario=Usuario::where('token',$token);
        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error','Token no válido');
        }
        else{
            //modificar a usuario confirmado
            $usuario->confirmado="1";
            $usuario->token="";
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta verificada correctamente');
        }
        $alertas =Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }
}