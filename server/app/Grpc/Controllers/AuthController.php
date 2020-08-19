<?php namespace App\Grpc\Controllers;

use App\Repositories\Interfaces\AuthInterface;
use ProtocolBuffer\Auth\AuthServiceInterface;
use Spiral\GRPC;
use ProtocolBuffer\Auth;
use App\Grpc\Interfaces\Validator;
use Illuminate\Contracts\Hashing\Hasher;
use Spiral\GRPC\Exception\InvokeException;
use Spiral\GRPC\StatusCode;
use ProtocolBuffer\Auth\SignUpRequest;
use ProtocolBuffer\Auth\Response;
use ProtocolBuffer\Auth\SignInRequest;
use Throwable;

class AuthController implements AuthServiceInterface
{

    /**
     * AuthInterface
     *
     * @var AuthInterface
     */
    private AuthInterface $user;

    /**
     * Input validator
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * Hasher
     *
     * @var Hasher
     */
    protected Hasher $hasher;

    /**
     * Create new instance.
     *
     * @param AuthInterface $user
     * @param Validator $validator
     * @param Hasher $hasher
     */
    public function __construct(AuthInterface $user, Validator $validator, Hasher $hasher)
    {
        $this->user = $user;
        $this->validator = $validator;
        $this->hasher = $hasher;
    }

    /**
     * @param GRPC\ContextInterface $ctx
     *
     * @param Auth\SignUpRequest $request
     * @return Auth\Response
     * @throws Throwable
     */
    public function SignUp(GRPC\ContextInterface $ctx, SignUpRequest $request): Response
    {
        $data = json_decode($request->serializeToJsonString(), true);

        $this->validator->validate($data, [
            'email' => 'bail|required|email|unique:users,email',
            'name' => 'required|max:255',
            'password' => 'required|confirmed',
        ]);

        $user = $this->user->Insert($data);

        $response = new Response();

        $response->setId($user->id);
        //TODO using jwt to handle token base auth
        $response->setToken("token");

        return $response;
    }

    /**
     * @param GRPC\ContextInterface $ctx
     * @param SignInRequest $in
     * @return Response
     * @throws Throwable
     */
    public function SignIn(GRPC\ContextInterface $ctx, SignInRequest $in): Response
    {
        $data = json_decode($in->serializeToJsonString(), true);

        $this->validator->validate($data, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = $this->user->GetByEmail($data['email']);

        if (!$this->hasher->check($data['password'], $user->password)) {
            throw new InvokeException("credentials error: ", StatusCode::INVALID_ARGUMENT);
        }

        $response = new Response();

        $response->setId($user->id);
        //TODO using jwt to handle token base auth
        $response->setToken("token");

        return $response;
    }
}
