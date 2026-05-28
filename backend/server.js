const express = require('express')
const mongoose = require('mongoose')
const cors = require('cors')

const app = express()

app.use(express.json())
app.use(cors())

mongoose.connect('YOUR_MONGO_URI')

const InquirySchema = new mongoose.Schema({
  name:String,
  email:String,
  phone:String,
  message:String,
  createdAt:{
    type:Date,
    default:Date.now
  }
})

const Inquiry = mongoose.model('Inquiry', InquirySchema)

app.post('/api/inquiry', async(req,res)=>{

  try{

    const inquiry = new Inquiry(req.body)

    await inquiry.save()

    res.json({
      success:true,
      message:'Inquiry Submitted Successfully'
    })

  }catch(error){

    res.status(500).json({
      success:false,
      message:'Server Error'
    })

  }

})

app.listen(5000, ()=>{
  console.log('Server Running on Port 5000')
})
