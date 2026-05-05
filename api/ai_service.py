# api/ai_service.py
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import uvicorn

app = FastAPI()

class Description(BaseModel):
    text: str

@app.get("/")
def read_root():
    return {"message": "Local AI Marketing Service for ImmoAffaire"}

@app.post("/generate-ads")
async def generate_ads(desc: Description):
    try:
        # Here you would integrate a local model like LLaMA-3 or Mistral via LangChain/Ollama
        # For now, we simulate the AI logic
        
        original_text = desc.text
        
        # Simulating AI transformation
        marketing_text = f"🌟 OPPORTUNITÉ EXCEPTIONNELLE EN CÔTE D'IVOIRE 🌟\n\n"
        marketing_text += f"Vous recherchez le bien idéal ? Ne cherchez plus !\n\n"
        marketing_text += f"📍 {original_text}\n\n"
        marketing_text += f"Profitez d'un cadre de vie unique et sécurisé. Prix compétitif !\n\n"
        marketing_text += f"📲 Contactez-nous maintenant pour une visite.\n\n"
        marketing_text += f"#immobilierCI #Abidjan #CoteDIvoire #InvestissementCI #VenteImmobiliere"
        
        return {
            "status": "success",
            "generated_text": marketing_text
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
