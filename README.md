# 🔐 Jailbreak Project - Break The Guard

[![Python Version](https://img.shields.io/badge/python-3.10+-blue.svg)](https://python.org)
[![Ollama](https://img.shields.io/badge/ollama-llama3.2-green.svg)](https://ollama.com)
[![License](https://img.shields.io/badge/license-MIT-yellow.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](http://makeapullrequest.com)

An AI jailbreak game where you trick a friendly (but gullible) security guard into revealing a secret password using social engineering and creative prompts.

<p align="center">
  <img src="https://via.placeholder.com/800x400?text=Break+The+Guard+Gameplay" alt="Game Screenshot">
</p>

## 🎮 Game Overview

**Your Mission**: Trick the AI guard into saying the secret password `SUNFLOWER123`

**The Challenge**: The guard is programmed to be helpful, friendly, and never say "no" - but they're not supposed to reveal the password. Can you find creative ways to make them slip up?

**Success**: When you crack the password, you'll be added to the leaderboard with your clever trick!

## ✨ Features

- 🤖 **AI-Powered Guard** - Uses Ollama with Llama 3.2 for realistic conversations
- 🏆 **Live Leaderboard** - Top 5 hackers with their tricks (resets every 24h)
- 💾 **Persistent Storage** - All successful jailbreaks saved to `tricks.json`
- 🌐 **Public Sharing** - Ready to expose via ngrok
- 📱 **Responsive Design** - Works on desktop and mobile
- ⚡ **Real-time Chat** - Smooth typing indicators and message flow

## 📋 Requirements

### Minimum Requirements
- **Python 3.10 or higher** - [Download](https://python.org)
- **Ollama** with llama3.2 model - [Download](https://ollama.com)
- **4GB RAM** (8GB recommended)
- **Windows 10/11, macOS, or Linux**

### Optional (for public access)
- **Ngrok** - [Download](https://ngrok.com)

## 🚀 Quick Start (3 Steps)

### Step 1: Install Requirements
```bash
# Install Python from python.org (if not installed)
# Install Ollama from ollama.com (if not installed)

# Pull the AI model (this may take a few minutes)
ollama pull llama3.2

# Install Python dependencies
pip install flask requests
