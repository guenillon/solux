<?php

namespace JPI\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JPIUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
