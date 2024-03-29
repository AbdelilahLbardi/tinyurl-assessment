<?php

return [

    /**
     * ----------
     * WARNING
     * ----------
     *
     *
     * in order to change the configuration. Make sure the division of "generation_limit / chunk_limit" results to
     * an integer. The applications will not behave correctly if the division generates a floating number.
     */



    /**
     * Sets the total of data that gets generated by the system
     */
    'generation_limit' => env('PRODUCTS_DUMMY_DATA_LIMIT', 100 * 1000 * 1000),

    /*
     * It is not recommended to create 100M at once, it should be created in multiple chunks
     * This params decides how many products should get generated per chunk
     */
    'chunk_limit' => env('PRODUCTS_DUMMY_DATA_LIMIT_CHUNK', 1 * 1000 * 1000),

];
