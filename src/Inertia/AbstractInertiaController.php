<?php

declare(strict_types=1);

namespace MagmaCore\Inertia;

//use App\Controller\Traits\BuildInertiaDefaultPropsTrait;
//use App\Entity\User;
use MagmaCore\Inertia\Service\InertiaInterface;
use MagmaCore\Base\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

//use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractInertiaController extends BaseController
{
    //use BuildInertiaDefaultPropsTrait;

    protected $inertia;

    //protected ValidatorInterface $validator;

    /**
     * @required
     */
    public function setInertia(InertiaInterface $inertia): void
    {
        $this->inertia = $inertia;
    }

    /**
     * @param array<string, mixed> $props
     * @param array<string, mixed> $viewData
     * @param array<string, mixed> $context
     */
    protected function renderWithInertia(
        string $component,
        array $props = [],
        array $viewData = [],
        array $context = []
    ): Response {

        /** @var ?User $currentUser */
        //$currentUser = $this->getUser();

        //$request = $this->get('request_stack')->getCurrentRequest();
        //$request = (new RequestStack())->getCurrentRequest();

        /*if ($request === null) {
            throw new \RuntimeException('There is no current request.');
        }*/

        //$defaultProps = $this->buildDefaultProps($request, $currentUser);
        $defaultProps = [];

        return $this->inertia->render($component, array_merge($defaultProps, $props), $viewData, $context);
    }
}