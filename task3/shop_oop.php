<?php

/**
 * Да се дополнат класите така што ќе се случи следново сценарио:
 *
 * 1. John ќе си ја наполни кошничката со продуктите кои се веќе иницијализирани во променливата $products
 * 2. За да купи што е можно повеќе производи John одлучил дека ќе ги сортира производите во кошницата по цена по
 *    опаѓачки редослед и ќе ги купува еден по еден додека има пари.
 * 3. Откако John ќе заврши со купувањето да се испечати кои производи му останале на John во кошницата и да се испечати
 *    уште колку пари му останале.
 *
 * Напомена: Може да се додаваат методи во класите но стартниот код не смее да се менува.
 */

class Product
{
    /**
     * @param  int  $id
     * @param  string  $name
     * @param  float  $price
     */
    public int $id;
    public string $name;
    public float $price;

    public function __construct(int $id, string $name,float $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function __toString(){
        return $this->id."\n".$this->name."\n".$this->price;
    }
}

class Cart
{
    /**
     * @param  Product[] $products
     */

    private array $products;
    public function __construct(array $products) {
        $this->products = $products;

    }

    /**
     * Sort the products in the cart in descending order by price
     *
     * @return void
     */
    public function sortByPrice(): void
    {
        usort($this->products, function($item1,$item2){
            return $item1->price > $item2->price ? -1:1;
        });
    }

    public function removeProduct($index){
        unset($this->products[$index]);
    }



    public function getProducts(){
        return $this->products;
    }

    public function setProducts($array){
        $this->products = $array;
        $this->sortByPrice();
    }
}

class Buyer
{
    /**
     * @param  string  $name
     * @param  Cart  $cart
     * @param  float  $money
     */

    public string $name;
    public Cart $cart;
    private float $money;

    public function __construct(string $name, Cart $cart, float $money) {
        $this->name = $name;
        $this->cart = $cart;
        $this->money = $money;
    }

    /**
     * @return void
     */

    //Se kupuvaat produkti po opagjacki redosled i koga ke nema dovolno pari za prviot proizvod, go preskoknuva i go kupuva sledniot,
    //dokolku ovaa logika sakame da ja smenime, namesto continue vo sledniot foreach, ke ima break.
    public function buyProducts(): void
    {
        $tempArray = $this->cart->getProducts();

        for($i = 0; $i < count($tempArray); $i++){
            if($this->cart->getProducts()[$i]->price > $this->money){
                continue;
            }
            else {

                $this->money -= $this->cart->getProducts()[$i]->price;
                $this->cart->removeProduct($i);

            }
        }

        $this->money = round($this->money,2); //floating precision error, mora ovaka.

    }

    /**
     * @return void
     */
    public function printRemainingCartProducts(): void
    {
        //print_r($this->cart->getProducts());
        $tempArray = $this->cart->getProducts();
        echo "Leftover products";

        foreach ($tempArray as $product){
            echo "\nID:".$product->id."\nName:".$product->name."\nPrice: ".$product->price."\n--------";
        }

    }

    /**
     * @return void
     */
    public function printRemainingMoney(): void
    {
        echo "\nLeftover money: ".$this->money;
    }

    public function addProducts($array){
        $this->cart->setProducts($array);
    }
}

$products = [
  new Product(1, "banana", 4.99),
  new Product(2, "apple", 9.55),
  new Product(3, "ice cream", 12),
  new Product(4, "yogurt", 13),
  new Product(5, "yogurt", 14)
];

$buyer = new Buyer("John", new Cart([]), 43.99);

$buyer->addProducts($products);

/**
 * Results
 */
$buyer->buyProducts();
$buyer->printRemainingCartProducts();
$buyer->printRemainingMoney();


