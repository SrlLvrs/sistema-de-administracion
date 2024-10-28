// src/supabase.js
import { createClient } from '@supabase/supabase-js';

// Añade aquí tu URL y API Key de Supabase
const supabaseUrl = import.meta.env.VITE_SUPABASE_URL;
const supabaseKey = import.meta.env.VITE_SUPABASE_KEY;
export const supabase = createClient(supabaseUrl, supabaseKey);
