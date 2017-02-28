<?php
namespace LaccUser\Console;

use Illuminate\Console\Command;
use LaccUser\Annotations\PermissionReader;
use LaccUser\Repositories\PermissionRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreatePermissionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laccuser:make-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criação de permissões baseado em controllers e actions.';

    /**
     * @var PermissionRepository
     */
    protected $repository;

    /**
     * @var PermissionReader
     */
    protected $reader;

    /**
     * CreatePermissionCommand constructor.
     *
     * @param PermissionRepository $repository
     * @param PermissionReader     $reader
     */
    public function __construct( PermissionRepository $repository, PermissionReader $reader )
    {
        parent::__construct();
        $this->repository = $repository;
        $this->reader     = $reader;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $permissions = $this->reader->getPermissions();
        foreach ( $permissions as $permission ):
            if ( !$this->existsPermission( $permission ) ) {
                $this->repository->create( $permission );
            }
        endforeach;
        $this->info( "<info>Permissões carregadas</info>" );
    }

    private function existsPermission( $permission )
    {
        $permission = $this->repository->findWhere( [
          'name'          => $permission[ 'name' ],
          'resource_name' => $permission[ 'resource_name' ],
        ] )->first();

        return $permission != null;
    }

}
