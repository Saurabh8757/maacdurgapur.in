<?php

namespace App\Services\Brands;

use LogicException;

class BrandContextManager
{
    private ?BrandContext $publicContext = null;

    public function setPublicContext(BrandContext $context): void
    {
        if (
            $this->publicContext
            && $this->publicContext->brand()->getKey() !== $context->brand()->getKey()
        ) {
            throw new LogicException(
                'A different public brand context is already bound to this request.'
            );
        }

        $this->publicContext = $context;
    }

    public function publicContext(): ?BrandContext
    {
        return $this->publicContext;
    }

    public function requirePublicContext(): BrandContext
    {
        if (!$this->publicContext) {
            throw new LogicException('No public brand context is bound to this request.');
        }

        return $this->publicContext;
    }
}
