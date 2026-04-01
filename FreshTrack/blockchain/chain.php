<?php
require_once 'block.php';

class Blockchain {
    public $chain;
    public $difficulty;

    public function __construct() {
        $this->chain = [$this->createGenesisBlock()];
        $this->difficulty = DIFFICULTY;
    }

    private function createGenesisBlock() {
        return new Block(0, date('Y-m-d H:i:s'), "Genesis Block", "0");
    }

    public function getLatestBlock() {
        return $this->chain[count($this->chain) - 1];
    }

    public function addBlock($newBlock) {
        $newBlock->previousHash = $this->getLatestBlock()->hash;
        $newBlock->mineBlock($this->difficulty);
        array_push($this->chain, $newBlock);
        
        // Save to file
        $this->saveToFile();
    }

    public function isChainValid() {
        for ($i = 1; $i < count($this->chain); $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];

            if ($currentBlock->hash !== $currentBlock->calculateHash()) {
                return false;
            }

            if ($currentBlock->previousHash !== $previousBlock->hash) {
                return false;
            }
        }
        return true;
    }

    public function saveToFile() {
        $data = serialize($this);
        file_put_contents(BLOCKCHAIN_DIR . 'blockchain.dat', $data);
    }

    public static function loadFromFile() {
        $file = BLOCKCHAIN_DIR . 'blockchain.dat';
        if (file_exists($file)) {
            $data = file_get_contents($file);
            return unserialize($data);
        }
        return new Blockchain();
    }
}
?>