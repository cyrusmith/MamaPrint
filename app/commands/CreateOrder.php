<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Catalog\CatalogItem;

class CreateOrder extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'order:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $workbook = CatalogItem::find(1);

        $order = new \Order\Order();

        $order->total = $workbook->price;
        $order->status = \Order\Order::STATUS_COMPLETE;
        $order->user()->associate(User::find(1));
        $order->save();

        $item = new \Order\OrderItem();
        $item->catalogItem()->associate($workbook);
        $item->price = $workbook->price;
        $order->items()->save($item);

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(//array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
