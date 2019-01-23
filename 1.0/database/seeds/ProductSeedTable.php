<?php

use Illuminate\Database\Seeder;

class ProductSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $product = new \App\Product([
            'imagePath' => 'images/AGoT.jpg',
            'title' => 'Game of Throne 1',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();

        $product = new \App\Product([
            'imagePath' => 'images/AGoT2.jpg',
            'title' => 'Game of Throne 2',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();

        $product = new \App\Product([
            'imagePath' => 'images/AGoT3.jpg',
            'title' => 'Game of Throne 3',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();

        $product = new \App\Product([
            'imagePath' => 'images/AGoT4.jpg',
            'title' => 'Game of Throne 4',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();

        $product = new \App\Product([
            'imagePath' => 'images/AGoT5.jpg',
            'title' => 'Game of Throne 5',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();

        $product = new \App\Product([
            'imagePath' => 'images/AGoT6.jpg',
            'title' => 'Game of Throne 6',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.',
            'price' => 12
        ]);
        $product->save();
    }
}
