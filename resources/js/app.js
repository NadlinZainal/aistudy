
import './bootstrap';

import OpenAI from "openai";

const openai = new OpenAI({
	apiKey: import.meta.env.VITE_OPENAI_API_KEY,
	dangerouslyAllowBrowser: true // Note: using API keys in client-side code is generally unsafe
});

const response = openai.responses.create({
	model: "gpt-5-nano",
	input: "write a haiku about ai",
	store: true,
});

response.then((result) => console.log(result.output_text));
