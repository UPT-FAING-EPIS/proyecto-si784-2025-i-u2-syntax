provider "aws" {
  region     = "us-east-1"
  access_key = var.aws_access_key
  secret_key = var.aws_secret_key
}

resource "aws_instance" "ejemplo" {
  ami           = "ami-0c55b159cbfafe1f0" # Ubuntu en us-east-1
  instance_type = "t2.micro"
  tags = {
    Name = "ServidorDeMentoria"
  }
}
