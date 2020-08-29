<?php


namespace app\middleware;


use app\domain\exceptions\AccessIsDeniedException;
use app\domain\repositories\IGraphRepository;
use app\domain\repositories\IUserRepository;
use app\infrastructure\helpers\AuthHelper;
use yii\base\ActionFilter;

class AccessFilter extends ActionFilter
{
    private $graphRepository;
    private $userRepository;

    public function __construct(IGraphRepository $graphRepository, IUserRepository $userRepository, $config = [])
    {
        $this->graphRepository = $graphRepository;
        $this->userRepository = $userRepository;

        parent::__construct($config);
    }

    public function beforeAction($action)
    {
        $graphId = \Yii::$app->request->getQueryParam('id');
        $graph = $this->graphRepository->getById($graphId);

        try{
            $user = AuthHelper::getAuthenticatedUser($this->userRepository);
            if ($graph->getUser() !== null && $graph->getUser() !== $user->getId()){
                throw new AccessIsDeniedException('Доступ запрещен');
            }
        }catch (\Exception $exception){
            if ($graph->getUser() !== null){
                throw new AccessIsDeniedException('Доступ запрещен');
            }
        }

        return parent::beforeAction($action);
    }
}