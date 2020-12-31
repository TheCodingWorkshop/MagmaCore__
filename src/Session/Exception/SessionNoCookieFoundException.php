<?php

declare(strict_types=1);

namespace MagmaCore\Session\Exception;

use LogicException;

class SessionNoCookieFoundException extends LogicException
{ }