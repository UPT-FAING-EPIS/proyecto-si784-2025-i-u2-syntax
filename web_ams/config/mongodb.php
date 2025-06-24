<?php
// config/mongodb.php
require_once BASE_PATH . '/vendor/autoload.php';

class MongoDB {
    
    public $client;
    public $database;
    public $collection;
    private $mongoUri;
    
    // Constantes de configuración
    private const DATABASE_NAME = 'test';
    private const COLLECTION_NAME = 'accions';
    private const KEYS_COLLECTION_NAME = 'keys_usuarios';
    
    // Configuración de índices
    private const INDICES_CONFIG = [
        self::KEYS_COLLECTION_NAME => [
            [
                'fields' => ['codigo_reclamo' => 1],
                'options' => ['unique' => true, 'name' => 'codigo_reclamo_unique'],
                'description' => 'Índice único para códigos de reclamo'
            ],
            [
                'fields' => ['usuario_id' => 1],
                'options' => ['name' => 'usuario_id_index'],
                'description' => 'Índice para búsquedas por usuario'
            ],
            [
                'fields' => ['fecha_expiracion' => 1],
                'options' => ['expireAfterSeconds' => 0, 'name' => 'expiracion_ttl'],
                'description' => 'Índice TTL para expiración automática'
            ],
            [
                'fields' => ['activo' => 1, 'usado' => 1],
                'options' => ['name' => 'activo_usado_index'],
                'description' => 'Índice compuesto para búsquedas de códigos activos'
            ],
            [
                'fields' => ['discord_username' => 1],
                'options' => ['name' => 'discord_username_index'],
                'description' => 'Índice para búsquedas por username de Discord'
            ],
            [
                'fields' => ['tipo_usuario' => 1],
                'options' => ['name' => 'tipo_usuario_index'],
                'description' => 'Índice para filtros por tipo de usuario'
            ],
            [
                'fields' => ['email' => 1],
                'options' => ['name' => 'email_index'],
                'description' => 'Índice para búsquedas por email'
            ]
        ]
    ];
    
    public function __construct($mongoUri = null) {
        try {
            $this->initializeConnection($mongoUri);
            $this->verifyConnection();
            $this->initializeCollections();
            
            error_log("✅ [MONGODB] Conexión establecida exitosamente a: " . self::DATABASE_NAME);
            
        } catch (Exception $e) {
            error_log("❌ [MONGODB] Error en constructor: " . $e->getMessage());
            throw new Exception("No se pudo conectar a MongoDB: " . $e->getMessage());
        }
    }
    
    private function initializeConnection($mongoUri) {
        $this->mongoUri = $mongoUri ?? $this->getDefaultMongoUri();
        
        if (!$this->mongoUri) {
            throw new Exception("MongoDB URI no configurada");
        }
        
        $this->client = new MongoDB\Client($this->mongoUri);
        $this->database = $this->client->selectDatabase(self::DATABASE_NAME);
        $this->collection = $this->database->selectCollection(self::COLLECTION_NAME);
    }
    
    private function getDefaultMongoUri() {
        return 'mongodb+srv://gh2022073898:3qh1hQb37GGB4HYY@cluster0.wfxnce1.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
    }
    
    private function verifyConnection() {
        $this->client->selectDatabase('admin')->command(['ping' => 1]);
    }
    
    private function initializeCollections() {
        $this->createCollectionIndexes();
    }
    
    private function createCollectionIndexes() {
        foreach (self::INDICES_CONFIG as $collectionName => $indices) {
            $this->createIndexesForCollection($collectionName, $indices);
        }
    }
    
    private function createIndexesForCollection($collectionName, $indices) {
        try {
            $collection = $this->database->selectCollection($collectionName);
            
            foreach ($indices as $indexConfig) {
                $this->createSingleIndex($collection, $indexConfig);
            }
            
            error_log("✅ [MONGODB] Índices verificados para colección: {$collectionName}");
            
        } catch (Exception $e) {
            error_log("⚠️ [MONGODB] Error creando índices para {$collectionName}: " . $e->getMessage());
        }
    }
    
    private function createSingleIndex($collection, $indexConfig) {
        try {
            $collection->createIndex(
                $indexConfig['fields'],
                $indexConfig['options']
            );
            
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') === false) {
                error_log("⚠️ [MONGODB] Error creando índice {$indexConfig['options']['name']}: " . $e->getMessage());
            }
        }
    }
    
    public function verificarConexion() {
        try {
            $this->client->selectDatabase('admin')->command(['ping' => 1]);
            return true;
        } catch (Exception $e) {
            error_log("❌ [MONGODB] Conexión perdida: " . $e->getMessage());
            return false;
        }
    }
    
    public function getMainCollection() {
        return $this->collection;
    }
    
    public function getKeysCollection() {
        return $this->database->selectCollection(self::KEYS_COLLECTION_NAME);
    }
    
    public function getCollection($collectionName) {
        return $this->database->selectCollection($collectionName);
    }
    
    public function obtenerEstadisticas() {
        try {
            $estadisticas = [
                'database' => self::DATABASE_NAME,
                'conexion_activa' => $this->verificarConexion(),
                'timestamp' => date('Y-m-d H:i:s'),
                'colecciones' => []
            ];
            
            $colecciones = [
                self::COLLECTION_NAME => 'Colección Principal',
                self::KEYS_COLLECTION_NAME => 'Códigos de Reclamo'
            ];
            
            foreach ($colecciones as $nombre => $descripcion) {
                $estadisticas['colecciones'][$nombre] = $this->getCollectionStats($nombre, $descripcion);
            }
            
            return [
                'success' => true,
                'data' => $estadisticas
            ];
            
        } catch (Exception $e) {
            error_log("❌ [MONGODB] Error obteniendo estadísticas: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }
    
    private function getCollectionStats($collectionName, $description) {
        try {
            $stats = $this->database->command([
                'collStats' => $collectionName
            ])->toArray()[0];
            
            return [
                'descripcion' => $description,
                'documentos' => $stats['count'] ?? 0,
                'tamano_mb' => round(($stats['size'] ?? 0) / 1024 / 1024, 2),
                'indices' => $stats['nindexes'] ?? 0,
                'existe' => true
            ];
            
        } catch (Exception $e) {
            return [
                'descripcion' => $description,
                'documentos' => 0,
                'tamano_mb' => 0,
                'indices' => 0,
                'existe' => false,
                'nota' => 'Colección no existe'
            ];
        }
    }
    
    public function ejecutarMantenimiento() {
        try {
            $resultados = [
                'timestamp' => date('Y-m-d H:i:s'),
                'operaciones' => []
            ];
            
            $resultados['operaciones']['limpieza_codigos'] = $this->limpiarCodigosExpirados();
            $resultados['operaciones']['eliminacion_antiguos'] = $this->eliminarCodigosAntiguos();
            $resultados['operaciones']['verificacion_indices'] = $this->verificarIndices();
            
            error_log("🧹 [MONGODB] Mantenimiento completado exitosamente");
            
            return [
                'success' => true,
                'data' => $resultados
            ];
            
        } catch (Exception $e) {
            error_log("❌ [MONGODB] Error en mantenimiento: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }
    
    private function limpiarCodigosExpirados() {
        try {
            $ahora = new MongoDB\BSON\UTCDateTime();
            $keysCollection = $this->getKeysCollection();
            
            $filtro = [
                'fecha_expiracion' => ['$lt' => $ahora],
                'activo' => true
            ];
            
            $actualizacion = [
                '$set' => [
                    'activo' => false,
                    'razon_invalidacion' => 'expirado_mantenimiento',
                    'fecha_invalidacion' => $ahora
                ]
            ];
            
            $resultado = $keysCollection->updateMany($filtro, $actualizacion);
            $procesados = $resultado->getModifiedCount();
            
            return [
                'operacion' => 'Limpieza de códigos expirados',
                'procesados' => $procesados,
                'success' => true
            ];
            
        } catch (Exception $e) {
            return [
                'operacion' => 'Limpieza de códigos expirados',
                'procesados' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function eliminarCodigosAntiguos() {
        try {
            $hace30Dias = new DateTime();
            $hace30Dias->sub(new DateInterval('P30D'));
            $fecha30Dias = new MongoDB\BSON\UTCDateTime($hace30Dias->getTimestamp() * 1000);
            
            $keysCollection = $this->getKeysCollection();
            $filtro = ['fecha_generacion' => ['$lt' => $fecha30Dias]];
            
            $resultado = $keysCollection->deleteMany($filtro);
            $eliminados = $resultado->getDeletedCount();
            
            return [
                'operacion' => 'Eliminación de códigos antiguos (>30 días)',
                'eliminados' => $eliminados,
                'success' => true
            ];
            
        } catch (Exception $e) {
            return [
                'operacion' => 'Eliminación de códigos antiguos',
                'eliminados' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function verificarIndices() {
        try {
            $keysCollection = $this->getKeysCollection();
            $indices = $keysCollection->listIndexes();
            
            $indicesInfo = [];
            foreach ($indices as $indice) {
                $indicesInfo[] = [
                    'nombre' => $indice['name'],
                    'keys' => $indice['key'],
                    'unique' => isset($indice['unique']) ? $indice['unique'] : false,
                    'ttl' => isset($indice['expireAfterSeconds'])
                ];
            }
            
            return [
                'operacion' => 'Verificación de índices',
                'total_indices' => count($indicesInfo),
                'indices' => $indicesInfo,
                'success' => true
            ];
            
        } catch (Exception $e) {
            return [
                'operacion' => 'Verificación de índices',
                'total_indices' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function cerrarConexion() {
        if ($this->client !== null) {
            error_log("🔒 [MONGODB] Cerrando conexión");
            $this->client = null;
            $this->database = null;
            $this->collection = null;
        }
    }
    
    public function __destruct() {
        $this->cerrarConexion();
    }
}