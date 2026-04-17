/*
  # Create Users Table for PHP Registration System

  ## Summary
  Sets up the users table to support a PHP-based user registration system.

  ## New Tables
  - `users`
    - `user_id` (serial, primary key)
    - `email` (text, unique, not null)
    - `pass` (text, not null) - stores bcrypt hash
    - `first_name` (text, not null)
    - `last_name` (text, not null)
    - `active` (boolean, default false) - whether account is active
    - `user_level` (text, default 'user') - 'user' or 'admin'
    - `registration_date` (timestamptz, default now())
    - `password_reset_token` (text, nullable) - for password reset flow
    - `password_reset_expires` (timestamptz, nullable)

  ## Security
  - RLS enabled
  - Policies restrict access to authenticated users only for their own data
  - Admin-level users can view all records via service role
*/

CREATE TABLE IF NOT EXISTS users (
    user_id SERIAL PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    pass TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    active BOOLEAN DEFAULT false,
    user_level TEXT DEFAULT 'user',
    registration_date TIMESTAMPTZ DEFAULT now(),
    password_reset_token TEXT,
    password_reset_expires TIMESTAMPTZ
);

ALTER TABLE users ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view own record"
    ON users FOR SELECT
    TO authenticated
    USING (auth.uid()::text = user_id::text);

CREATE POLICY "Service role can insert users"
    ON users FOR INSERT
    TO service_role
    WITH CHECK (true);

CREATE POLICY "Service role can update users"
    ON users FOR UPDATE
    TO service_role
    USING (true)
    WITH CHECK (true);

CREATE POLICY "Service role can select all users"
    ON users FOR SELECT
    TO service_role
    USING (true);
