<?php   namespace App\Http\Controllers\Api\V1;

use App\Grpc\Interfaces\ClientFactory;
use App\Grpc\Interfaces\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ProtocolBuffer\Auth\AuthServiceInterface;
use ProtocolBuffer\Auth\SignInRequest;
use ProtocolBuffer\Auth\SignUpRequest;

class AuthController extends Controller
{
    protected ClientFactory $grpcClientFactory;


    protected ErrorHandler $errorHandler;


    public function __construct(ClientFactory $grpcClientFactory, ErrorHandler $errorHandler)
    {
        $this->grpcClientFactory = $grpcClientFactory;
        $this->errorHandler = $errorHandler;
    }


    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $client = $this->grpcClientFactory->make(AuthServiceInterface::class);

        $request = new SignUpRequest();

        $request->setEmail($request->input("email"));
        $request->setName($request->input("name"));
        $request->setPassword($request->input("password"));
        $request->setPasswordConfirmation($request->input("password_confirmation"));

        [$response, $status] = $client->Register($request)->wait();

        $this->errorHandler->handle($status, 3);

        $data = [
            "id" => $response->getId(),
            "token" => $response->getToken()
        ];

        return response()->json(json_encode($data));
    }


    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = $this->grpcClientFactory->make(AuthServiceInterface::class);

        $request = new SignInRequest();

        $request->setEmail($request->input("email"));
        $request->setPassword($request->input("password"));

        [$response, $status] = $client->Login($request)->wait();

        $this->errorHandler->handle($status, 3);

        $data = [
            "id" => $response->getId(),
            "token" => $response->getToken()
        ];

        return response()->json(json_encode($data));
    }
}
