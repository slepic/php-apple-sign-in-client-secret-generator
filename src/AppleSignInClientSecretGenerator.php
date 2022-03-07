<?php

declare(strict_types=1);

namespace Slepic\AppleSignInClientSecretGenerator;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

final class AppleSignInClientSecretGenerator
{
    private const DEFAULT_TTL = 15552000;

    /**
     * @var int time to live for token
     */
    private readonly int $ttl;

    /**
     * @param int|null $ttl
     */
    public function __construct(?int $ttl = null)
    {
        $this->ttl = $ttl ?? self::DEFAULT_TTL;
    }

    /**
     * Generate new token.
     *
     * @return string
     */
    public function generate(string $clientId, string $teamId, string $keyId, string $privateKeyContent): string
    {
        $algorithmManager = new AlgorithmManager([new ES256()]);
        $jwsBuilder = new JWSBuilder($algorithmManager);

        $privateECKey = JWKFactory::createFromKey($privateKeyContent, null, [
            'kid' => $keyId,
            'alg' => 'ES256',
        ]);
        $protectedHeader = [
            'alg' => 'ES256',
            'kid' => $privateECKey->get('kid'),
        ];

        $time = time();
        $payload = [
            'iss' => $teamId,
            'iat' => $time,
            'exp' => $time + $this->ttl,
            'aud' => 'https://appleid.apple.com',
            'sub' => $clientId,
        ];

        $jws = $jwsBuilder
            ->create()
            ->withPayload(json_encode($payload))
            ->addSignature($privateECKey, $protectedHeader)
            ->build();

        return (new CompactSerializer())->serialize($jws);
    }
}
