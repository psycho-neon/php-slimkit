<?php

declare(strict_types=1);

namespace App\Support;

use Cake\Chronos\Chronos;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\ConstraintViolation;

final class JwtAuth
{
    /** @var Configuration */
    private Configuration $configuration;

    /** @var string The issuer name */
    private string $issuer;

    /** @var int Max Lifetime in seconds */
    private int $lifetime;

    /**
     * @param Configuration $configuration
     * @param string        $issuer
     * @param int           $lifetime
     */
    public function __construct(Configuration $configuration, string $issuer, int $lifetime)
    {
        $this->configuration = $configuration;
        $this->issuer = $issuer;
        $this->lifetime = $lifetime;
    }

    /**
     * Max Lifetime of the token.
     *
     * @return int
     */
    public function getLifeTime(): int
    {
        return $this->lifetime;
    }

    /**
     * Create a JSON web token.
     *
     * @param array<string> $claims The claims
     *
     * @return string
     */
    public function createJwt(array $claims): string
    {
        $now = Chronos::now();

        $builder = $this->configuration->builder()
            ->issuedBy($this->issuer)
            ->issuedAt($now)
            ->expiresAt($now->addSeconds($this->lifetime));

        // Add claims
        foreach ($claims as $name => $value) {
            $builder = $builder->withClaim($name, $value);
        }

        // Builds a new token using our secret keyword
        return $builder->getToken(
            $this->configuration->signer(),
            $this->configuration->signingKey()
        )->toString();
    }

    /**
     * Parse token.
     *
     * @param string $token The JWT
     *
     * @throws ConstraintViolation
     *
     * @return Plain The Parsed token
     */
    private function createParsedToken(string $token): Plain
    {
        $token = $this->configuration->parser()->parse($token);

        if (!$token instanceof Plain) {
            throw new ConstraintViolation('You should pass a plain token');
        }

        return $token;
    }

    /**
     * Validate the access token.
     *
     * @param string $accessToken The JWT
     *
     * @return Plain|null The token, if valid
     */
    public function validateToken(string $accessToken): ?Plain
    {
        $token = $this->createParsedToken($accessToken);
        $constraints = $this->configuration->validationConstraints();

        // Token signature must be valid
        $constraints[] = new SignedWith($this->configuration->signer(), $this->configuration->verificationKey());

        // Check whether the issuer is the same
        $constraints[] = new IssuedBy($this->issuer);

        // Check whether the token has not expired
        $constraints[] = new ValidAt(new SystemClock(Chronos::now()->getTimezone()));

        if (!$this->configuration->validator()->validate($token, ...$constraints)) {
            return null;
        }

        // custom claim validation
        $userId = $token->claims()->get('uid');
        if (!isset($userId)) {
            return null;
        }

        return $token;
    }
}
