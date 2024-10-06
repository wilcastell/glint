<?php
use PHPUnit\Framework\TestCase;
use Lib\Pagination;
use Lib\PaginationConfig;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $table = 'items';
  protected $fillable = ['name'];
}

class PaginationTest extends TestCase
{
  public function testPaginateEloquentWithDatabase()
  {
    // Crear una conexión en memoria a SQLite
    $db = new \Illuminate\Database\Capsule\Manager();
    $db->addConnection([
      'driver' => 'sqlite',
      'database' => ':memory:',
    ]);
    $db->setAsGlobal();
    $db->bootEloquent();

    // Crear una tabla temporal en SQLite
    $db->schema()->create('items', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->timestamps();
    });

    // Insertar datos usando el modelo Eloquent
    for ($i = 1; $i <= 100; $i++) {
      Item::create(['name' => "Item $i"]);
    }

    // Crear la consulta con el builder Eloquent
    $query = Item::query(); // Usar el modelo Eloquent

    // Configurar el objeto PaginationConfig
    $config = new PaginationConfig('name', 10, 1, '', 'id', 'asc', []);

    // Ejecutar la paginación
    $result = Pagination::paginate($query, $config);

    // Comprobar que los resultados sean correctos
    $this->assertArrayHasKey('items', $result);
    $this->assertIsArray($result['items']); // Verificar que 'items' es un array, no una colección
    $this->assertEquals(100, $result['total']);
    $this->assertCount(10, $result['items']); // 10 items por página
  }

  public function testPaginateArray()
  {
    // Crear un array de ejemplo con 50 elementos
    $dataArray = array_fill(0, 50, 'item');

    // Crear una instancia de PaginationConfig con parámetros
    $config = new PaginationConfig(
      'campo',
      10,     // Per page
      1,      // Page
      '',     // Search
      'id',   // Order by
      'asc',  // Order direction
      []      // Additional filters
    );

    // Ejecutar la paginación en el array
    $result = Pagination::paginate($dataArray, $config);

    // Comprobar que el resultado tiene la estructura correcta
    $this->assertArrayHasKey('items', $result);
    $this->assertArrayHasKey('total', $result);
    $this->assertArrayHasKey('currentPage', $result);
    $this->assertArrayHasKey('totalPages', $result);

    // Comprobar que los valores sean los esperados
    $this->assertEquals(50, $result['total']);
    $this->assertEquals(1, $result['currentPage']);
    $this->assertEquals(5, $result['totalPages']);
    $this->assertCount(10, $result['items']); // 10 items por página
  }

  public function testPaginateWithSearchAndFilters()
  {
    // Crear una conexión en memoria a SQLite
    $db = new \Illuminate\Database\Capsule\Manager();
    $db->addConnection([
      'driver' => 'sqlite',
      'database' => ':memory:',
    ]);
    $db->setAsGlobal();
    $db->bootEloquent();

    // Crear una tabla temporal en SQLite
    $db->schema()->create('items', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->string('status');
    });

    // Insertar datos con diferentes estados
    for ($i = 1; $i <= 50; $i++) {
      $db->table('items')->insert(['name' => "Item $i", 'status' => $i % 2 === 0 ? 'active' : 'inactive']);
    }

    // Crear la consulta con el builder real
    $query = $db->table('items');

    // Crear una configuración de paginación con un término de búsqueda y filtros adicionales
    $config = new PaginationConfig(
      'name', // Asegúrate de que el campo "name" existe en la tabla
      10,     // Per page
      1,      // Page
      'Item', // Search term
      'id',   // Order by
      'asc',  // Order direction
      [['campo' => 'status', 'operador' => '=', 'valor' => 'active']] // Additional filters
    );

    // Ejecutar la paginación con la consulta real
    $result = Pagination::paginate($query, $config);

    // Comprobar que los resultados contienen el filtro y la búsqueda aplicados
    $this->assertArrayHasKey('items', $result);
    $this->assertCount(10, $result['items']); // Solo debe haber 10 items activos en la primera página
    $this->assertEquals(25, $result['total']); // Debe haber un total de 25 items activos
  }

  public function testPaginateRawSql()
  {
    // Crear una conexión en memoria a SQLite
    $db = new \Illuminate\Database\Capsule\Manager();
    $db->addConnection([
      'driver' => 'sqlite',
      'database' => ':memory:',
    ]);
    $db->setAsGlobal();
    $db->bootEloquent();

    // Establecer la conexión en la clase Pagination
    Pagination::setConnection($db->getConnection());

    // Crear una tabla temporal en SQLite
    $db->schema()->create('items', function ($table) {
      $table->increments('id');
      $table->string('name');
    });

    // Insertar datos de prueba
    for ($i = 1; $i <= 50; $i++) {
      $db->table('items')->insert(['name' => "Item $i"]);
    }

    // Crear una consulta SQL directa
    $rawSql = "SELECT * FROM items WHERE name LIKE '%Item%'";

    // Configurar el objeto PaginationConfig
    $config = new PaginationConfig('name', 10, 1, '', 'id', 'asc', []);

    // Ejecutar la paginación con la consulta SQL directa
    $result = Pagination::paginate($rawSql, $config);

    // Comprobar que los resultados sean correctos
    $this->assertArrayHasKey('items', $result);
    $this->assertIsArray($result['items']);
    $this->assertEquals(50, $result['total']);
    $this->assertCount(10, $result['items']); // 10 items por página
    $this->assertEquals(1, $result['currentPage']);
    $this->assertEquals(5, $result['totalPages']);
  }



}
