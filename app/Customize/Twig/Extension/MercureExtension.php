<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Twig\Extension;

use Customize\Mercure\JwtProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MercureExtension extends AbstractExtension
{
    /**
     * @var JwtProvider
     */
    private $jwtTokenProvider;

    /**
     * TaxExtension constructor.
     */
    public function __construct(JwtProvider $jwtTokenProvider)
    {
        $this->jwtTokenProvider = $jwtTokenProvider;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[] An array of functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('mercure_get_token', [$this, 'mercureGetToken']),
        ];
    }

    public function mercureGetToken()
    {
        $token = $this->jwtTokenProvider->getSubcribleToken();

        return $token;
    }
}
