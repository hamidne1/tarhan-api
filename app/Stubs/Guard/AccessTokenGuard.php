<?php

namespace App\Stubs\Guard;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class AccessTokenGuard implements Guard {

    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the query string item from the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     * @param  \Illuminate\Http\Request $request
     * @param  string $inputKey
     * @param  string $storageKey
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request, $inputKey = 'access_token', $storageKey = 'access_token')
    {
        $this->setProvider($provider);
        $this->request = $request;
        $this->inputKey = $inputKey;
        $this->storageKey = $storageKey;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {

            $user = $this->retrieveUserByToken($token);

        }

        return $this->user = $user;
    }

    /**
     * retrieve the user by  token
     *
     * @param $token
     * @return mixed|null
     */
    protected function retrieveUserByToken($token)
    {
        $token = Token::where($this->storageKey, $token)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        return $token ? $token->user : null;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        if (empty($token)) {
            $token = $this->request->getPassword();
        }

        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        return $this->retrieveUserByToken($credentials[$this->inputKey])
            ? true : false;
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $user->verify_code == $credentials['verify_code']) {
            $tokenObject = $user->tokens()->create([
                $this->storageKey => str_random(60),
                'expire_at' => Carbon::now()->addDays(
                    config('auth.tokens.expire')
                )
            ]);

            return $tokenObject[$this->storageKey];
        }
        return false;
    }


    public function logout()
    {
        $token = $this->getTokenForRequest();

        if ($token)
            Token::where('access_token', $token)->delete();

        $this->user = null;
    }


}