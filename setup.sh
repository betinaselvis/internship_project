#!/bin/bash

# This script runs the database schema setup
# Railway will execute this during deployment if configured

# Wait for MySQL to be ready
sleep 5

# Run the schema
php apply_schema.php
