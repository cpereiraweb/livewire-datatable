<?php

namespace LuanFreitasDev\LivewireDataTables\Traits;

use LuanFreitasDev\LivewireDataTables\Button;

trait ActionButton
{
    /**
     * @var array
     */
    public array $actionRoutes = [];
    /**
     * @var array
     */
    public array $actionBtns = [];

    public function initActions()
    {
        $this->actionBtns = $this->actions();

        foreach ($this->actionBtns as $actionBtn) {
            if (isset($actionBtn['route'])) {
                $this->actionRoutes[$actionBtn['action']] = $actionBtn['route'];
            }
        }
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            Button::add('edit')
                ->caption('Editar')
                ->class('btn btn-primary')
                ->route('user.edit', ['id'])
                ->make(),

            Button::add('delete')
                ->caption('Excluir')
                ->class('btn btn-danger')
                ->route('user.edit', ['product' => 'id'])
                ->make(),
        ];
    }

    /**
     * @param string $action
     * @param string $params
     * @param string $url
     */
    public function actionCall(string $action, string $params, string $url = '')
    {

        $this->redirect(route(\Arr::get($this->actionRoutes, $action), json_decode($params, true)));

    }
}
