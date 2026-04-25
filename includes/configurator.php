<?php
/**
 * ALEX Project Configurator
 * Interactive pricing and solution builder.
 */
?>
<div id="alex-configurator-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.95); z-index:5000; overflow-y:auto; padding:40px 20px;">
    <div class="container" style="max-width:800px; margin:0 auto;">
        <div class="glass" style="padding:60px; position:relative; border: 1px solid var(--primary-color);">
            <button onclick="toggleConfigurator()" style="position:absolute; top:20px; right:20px; background:none; border:none; color:white; font-size:1.5rem; cursor:pointer;"><i class="fas fa-times"></i></button>
            
            <div id="config-step-1" class="config-step">
                <h2 style="font-size:2.5rem; margin-bottom:20px;">What are we building today?</h2>
                <p style="color:var(--text-muted); margin-bottom:40px;">Select the core nature of your deployment.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <button class="glass configurator-option" onclick="nextConfigStep(2, 'Enterprise Web')" style="padding:40px; text-align:center; color:white; cursor:pointer;">
                        <i class="fas fa-browser fa-3x" style="color:var(--primary-color); margin-bottom:15px;"></i><br>Enterprise Web
                    </button>
                    <button class="glass configurator-option" onclick="nextConfigStep(2, 'Mobile Application')" style="padding:40px; text-align:center; color:white; cursor:pointer;">
                        <i class="fas fa-mobile-android fa-3x" style="color:var(--secondary-color); margin-bottom:15px;"></i><br>Mobile App
                    </button>
                    <button class="glass configurator-option" onclick="nextConfigStep(2, 'AI & ML Engine')" style="padding:40px; text-align:center; color:white; cursor:pointer;">
                        <i class="fas fa-brain-circuit fa-3x" style="color:var(--accent-color); margin-bottom:15px;"></i><br>AI / ML Engine
                    </button>
                    <button class="glass configurator-option" onclick="nextConfigStep(2, 'Blockchain Node')" style="padding:40px; text-align:center; color:white; cursor:pointer;">
                        <i class="fas fa-link fa-3x" style="color:#f6c23e; margin-bottom:15px;"></i><br>Blockchain
                    </button>
                </div>
            </div>

            <div id="config-step-2" class="config-step" style="display:none;">
                <h2 style="font-size:2.5rem; margin-bottom:20px;">Integration Scale</h2>
                <p style="color:var(--text-muted); margin-bottom:40px;">How many modules will ALEX be managing?</p>
                <div style="display:flex; flex-direction:column; gap:20px;">
                    <button class="glass configurator-option" onclick="nextConfigStep(3, '1-3 Modules')" style="padding:20px; color:white; text-align:left;">
                        <strong>Standard</strong>: 1-3 Core Modules (Auth, DB, Basic API)
                    </button>
                    <button class="glass configurator-option" onclick="nextConfigStep(3, '4-10 Modules')" style="padding:20px; color:white; text-align:left;">
                        <strong>Enhanced</strong>: 4-10 Modules (CRM, Payments, NLP)
                    </button>
                    <button class="glass configurator-option" onclick="nextConfigStep(3, 'Global Scale')" style="padding:20px; color:white; text-align:left;">
                        <strong>Enterprise</strong>: 10+ Modules (Global sync, High Latency Load Balancing)
                    </button>
                </div>
            </div>

            <div id="config-step-3" class="config-step" style="display:none;">
                <h2 style="font-size:2.5rem; margin-bottom:20px;">Neural Analysis Complete</h2>
                <div id="config-result" style="background:rgba(99,102,241,0.1); padding:40px; border-radius:12px; border:1px solid var(--primary-color); margin-bottom:40px;">
                    <div style="font-size:0.9rem; color:var(--primary-color); font-weight:700; margin-bottom:10px;">GENERATED QUOTE</div>
                    <div id="quoted-price" style="font-size:3rem; font-weight:800; margin-bottom:10px;">₹ 45,000*</div>
                    <div id="quoted-summary" style="color:var(--text-muted);">Enterprise Web with Enhanced Integration.</div>
                </div>
                <button class="btn btn-primary" style="width:100%; padding:20px; font-size:1.2rem;" onclick="submitConfig()">Proceed to Neural Workspace</button>
            </div>
        </div>
    </div>
</div>

<style>
.configurator-option { transition: all 0.3s; border: 1px solid var(--glass-border); }
.configurator-option:hover { border-color: var(--primary-color); background: rgba(99,102,241,0.1); transform: translateY(-5px); }
</style>
