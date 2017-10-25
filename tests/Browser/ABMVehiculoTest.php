<?php

namespace Tests\Browser;

use Illuminate\Database\Seeder;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Vehiculo;
use App\User;

class ABMVehiculoTest extends DuskTestCase
{
    private $vehiculo;
    private $usuarioAdmin;
    private $password;
    private $vehiculoEdit;

  public function setUp()
    {
      parent::setUp();
      $this->vehiculo = factory(Vehiculo::class)->make();
      $this->vehiculoEdit = factory(Vehiculo::class)->make();
      $this->usuarioAdmin=factory(User::class)->create(['admin'=> '1', 'password'=> bcrypt('123456')]);
      $this->password = '123456';
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */

    public function testCreateVehicle()
    {
        $this->browse(function (Browser $browser) {
        $browser->visit('/vehiculo/create')
                ->type('usuario',$this->usuarioAdmin->usuario)
                ->type('password','123456')
                ->press('Iniciar')
                ->type('patente',$this->vehiculo->patente)
                ->type('num_movil',$this->vehiculo->num_movil)
                ->select('estado',$this->vehiculo->estado)
                ->type('detalle',$this->vehiculo->detalle)
                ->press('Registrar')
                ->assertSee('Lista de vehiculos');
        });
    }

    public function testUpdateVehicle()
    {
      $this->browse(function (browser $browser){
      $browser->visit('/vehiculo')
              ->click('.glyphicon-edit')
              ->type('patente', $this->vehiculoEdit->patente)
              ->type('num_movil', $this->vehiculoEdit->num_movil)
              ->select('estado',$this->vehiculoEdit->estado)
              ->type('detalle',$this->vehiculoEdit->detalle)
              ->press('Editar')
              ->assertSee('Lista de vehiculos');
              $this->usuarioAdmin->delete();
      });
    }

  public function testDeleteVehicle()
   {
     $patente = strtoupper($this->vehiculo->patente);
     $this->browse(function(browser $browser){
     $browser->visit('/vehiculo')
             ->click('.glyphicon-trash')
             ->with('.table', function ($table) {
             $table->assertSee('Lista de vehiculos');
     });
     $this->usuarioAdmin->delete();
   });
  }

  public function tearDown()
  {
    if ($this->vehiculoEdit){
        Vehiculo::destroy($this->vehiculoEdit->id);
    }
    if ($this->vehiculo){
        Vehiculo::destroy($this->vehiculo->id);
    }
  }
}
