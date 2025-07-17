<?php

namespace Hanafalah\ApiHelper\Commands;

use Hanafalah\ApiHelper\Concerns\ApiAccessPrompt;
use Illuminate\Support\Str;

class GenerateRsKeyCommand extends EnvironmentCommand
{
    use ApiAccessPrompt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper:generate-key {--app-code= : App Code} {--algorithm : Encrypt method} {--reference-id= : Reference id for api access} {--reference-type= : Reference type for api access}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate RS Key (256, 384, 512)';

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {
        $algorithm = $this->option('algorithm');
        if (isset($algorithm)) {
            $fam = Str::substr($algorithm, 0, 2);
        }
        list($public, $private) = $this->generate($algorithm, $fam);
        $appCode   = $this->option('app-code');
        if (isset($appCode)) {
            $apiAccess = $this->ApiAccessModel()->where('app_code', $appCode)->first();
            $apiAccess->public_key     = $public;
            $apiAccess->private_key    = $private;
            $apiAccess->reference_id   = $this->option('reference-id');
            $apiAccess->reference_type = $this->option('reference-type');
            $apiAccess->save();
        }
    }

    /**
     * Generate RS Key
     *
     * @param string|null $algorithm
     *
     * @return array
     */
    protected function generate($algorithm = null, ?string $family = null)
    {
        $algorithm = $this->chooseAlgorithm($algorithm, $family);
        $this->info('Generating using ' . $algorithm . ' key...');
        switch ($family) {
            case 'RS':
                $key = openssl_pkey_new([
                    "digest_alg" => $algorithm,
                    "private_key_bits" => 2048,
                    "private_key_type" => OPENSSL_KEYTYPE_RSA,
                ]);
                openssl_pkey_export($key, $privateKey);
                $details   = openssl_pkey_get_details($key);
                $publicKey = $details['key'];
            break;
            case 'ES':
                $keyPair    = sodium_crypto_sign_keypair();
                $privateKey = base64_encode(sodium_crypto_sign_secretkey($keyPair));
                $publicKey  = base64_encode(sodium_crypto_sign_publickey($keyPair));
            break;
        }
        $this->info("Private Key:\n" . $privateKey);
        $this->info("Public Key:\n" . $publicKey);
        return [$publicKey, $privateKey];
    }
}
