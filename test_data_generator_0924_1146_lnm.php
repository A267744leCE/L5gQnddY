<?php
// 代码生成时间: 2025-09-24 11:46:07
 * It is designed to be easily extendable and maintainable.
 */
class TestDataGenerator
{

    /**
     * Generate random data for testing purposes.
     *
     * @param array $fields Field definitions for the data to be generated.
     * @return array Generated test data.
     * @throws InvalidArgumentException If the fields parameter is invalid.
     */
    public function generate(array $fields): array
    {
        if (empty($fields)) {
            throw new InvalidArgumentException('Fields array cannot be empty.');
        }

        $data = [];

        foreach ($fields as $field => $definition) {
            if (!isset($definition['type'])) {
                throw new InvalidArgumentException(