<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

//REQUESTS 
use App\Http\Requests\UserRequest;
//MODELS
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user = new User;
        
        $data = $user->getUsers($request->all());
        return view('users.index', ['dataUsers' => $data]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            if(isset($request['password']) && !empty($request['password'])){
                $password = bcrypt($request['password']);
            }

            $nameFile = "";
            if ($request->hasFile('userPhoto')) {
                $newName = uniqid(date("HisYmd"));
                $nameFile = $newName . "." . $request->file("userPhoto")->extension();
    
                $upload = $request->file("userPhoto")->storeAs("public/UsersPhotos/", $nameFile);
    
                if (!$upload) {
                    return [
                        'error' => true,
                        'msg' => 'Falha ao fazer upload da imagem!'
                    ];
                }
            }
    
            User::create([
                "name"              => isset($request["name"]) ? $request["name"] : null,
                "last_name"         => isset($request["last_name"]) ? $request["last_name"] : null,
                "data_nascimento"   => isset($request["data_nascimento"]) ? $request["data_nascimento"] : null,
                "active"            => isset($request["active"]) ? $request["active"] : null,
                "email"             => isset($request["email"]) ? $request["email"] : null,
                "cell"              => isset($request["cell"]) ? $request["cell"] : null,
                "password"          => isset($request["password"]) ? $password : null,
                "url_photo"         => ($nameFile) ? $nameFile : null
            ]);

            return [
                'error' => false,
                'msg' => 'Novo usuário cadastrado!'
            ];

        }catch(\Exception $error){
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o usuário, tente de novo.'
            ];
        }
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = new User;

        return view('users.edit', [
            'user' => $user->find($id)
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try{
            if(isset($request) && !empty($request['password']) && !empty($request['password_confirmation'])){
                if($request['password'] === $request['password_confirmation']){
                    $password = bcrypt($request['password']);
                }
            }

            $user = DB::table('users')->where('id', $id)->first();

            $nameFile = "";
            if ($request->hasFile('userPhoto')) {
                $newName = uniqid(date("HisYmd"));
                $nameFile = $newName . "." . $request->file("userPhoto")->extension();
    
                $upload = $request->file("userPhoto")->storeAs("public/UsersPhotos/", $nameFile);
    
                if (!$upload) {
                    return [
                        'error' => true,
                        'msg' => 'Falha ao fazer upload da imagem!'
                    ];
                }
            }
    
            User::where('id', $id)->update([
                "name"              => isset($request["name"]) ? $request["name"] : $user->name,
                "last_name"         => isset($request["last_name"]) ? $request["last_name"] : $user->last_name,
                "data_nascimento"   => isset($request["data_nascimento"]) ? $request["data_nascimento"] : $user->data_nascimento,
                "active"            => isset($request["active"]) ? $request["active"] : $user->active,
                "email"             => isset($request["email"]) ? $request["email"] : $user->email,
                "cell"              => isset($request["cell"]) ? $request["cell"] : $user->cell,
                "password"          => isset($request["password"]) ? $password : $user->password,
                "url_photo"         => ($nameFile) ? $nameFile : $user->url_photo
            ]);
            
            return [
                'error' => false,
                'msg' => 'Registro salvo com sucesso!'
            ];

        }catch(\Exception $error){
            return [
                'error' => true,
                'msg' => 'Não foi possível salvar o registro, tente de novo',
                'error_message' => $error->getMessage()
            ];
        }
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = new User;

        $data = $user->deleteUser($id);
        return response()->json($data);
    }
}
