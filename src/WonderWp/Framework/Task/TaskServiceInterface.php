<?php

namespace WonderWp\Framework\Task;

interface TaskServiceInterface
{
    /**
     * Typically Where you'll have your \WP_CLI::add_command calls
     * @return mixed
     */
    public function registerCommands();
}
