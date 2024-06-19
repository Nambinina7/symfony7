<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Holyday;
use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class  EmployeeEntitiesStateProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface    $persistProcessor,
        private TokenStorageInterface $token,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {

        $currentUser = $this->token->getToken()->getUser();
        if (!$currentUser instanceof User) {
            throw new \RuntimeException('Expected instance of User');
        }
        if ($data instanceof Holyday) {
            $data->addUserHolyday($currentUser);
        }

        if ($data instanceof Permission) {
            $data->setUserPermissions($currentUser);
        }

        $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}
