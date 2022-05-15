library(keras)
library(tidyverse)
library(tidymodels)
rm(list = ls())

setwd("./Data607_FinalProject")


###################################################################
#### Now let's see if we can bring in .CSV we generated with the bake
#### of our RECIPE from project4 in Dsta607

emails_df <- read_csv("emails_baked.csv")

emails_df2 <- emails_df %>% 
  mutate(type2 = if_else(type == "ham", 0, 1))

emails_df2$type <- emails_df2$type2
emails_df2 <- emails_df2[,-502]

emails_split <- initial_split(emails_df2)
emails_train <- training(emails_split)
emails_test <- testing(emails_split)

y_train <- as.matrix(emails_train$type)
x_train <- as.matrix(emails_train[,-1])
y_test <- as.matrix(emails_test$type)
x_test <- as.matrix(emails_test[,-1])

y_train <- to_categorical(y_train, 2)
y_test <- to_categorical(y_test, 2)


########################################

dense_model <- keras_model_sequential() 

dense_model %>% 
  layer_dense(units = 128, activation = 'relu', input_shape = c(500)) %>% 
  layer_dropout(rate = 0.3) %>% 
  layer_dense(units = 32, activation = 'relu') %>%
  layer_dropout(rate = 0.2) %>%
  layer_dense(units = 2, activation = 'softmax')

summary(dense_model)


dense_model %>% compile(
  loss = 'categorical_crossentropy',
  optimizer = optimizer_rmsprop(),
  metrics = c('accuracy')
)

history <- dense_model %>% fit(
  x_train, y_train, 
  epochs = 30, batch_size = 128, 
  validation_split = 0.2
)

plot(history)

dense_model %>% evaluate(x_test, y_test)

#########
# OR you can do same here below

scores <- dense_model %>% evaluate(
  x_test, y_test, verbose = 0
)

cat('Test loss:', scores[[1]], '\n')
cat('Test accuracy:', scores[[2]], '\n')

####
library(ramify)
predict_y <- dense_model %>% predict(x_test)
y_pred <- argmax(predict_y, rows=TRUE)
y_pred
y_test
y_testsimplified <- argmax(y_test, rows=TRUE)
y_testsimplified

table(y_pred, y_testsimplified)

#######

##############

simple_cnn_model <- keras_model_sequential() %>%
  layer_embedding(input_dim = 500, output_dim = 16) %>%
  layer_dropout(rate = 0.2) %>%
  layer_conv_1d(filter = 32, kernel_size = 5, activation = "relu") %>%
  layer_max_pooling_1d(pool_size = 2) %>%
  layer_conv_1d(filter = 64, kernel_size = 3, activation = "relu") %>%
  #layer_dropout(rate = 0.2) %>%
  layer_global_max_pooling_1d() %>%
  layer_dense(units = 16, activation = "relu") %>%
  layer_dropout(rate = 0.2) %>%
  layer_dense(units = 2, activation = "sigmoid")

simple_cnn_model


simple_cnn_model %>% compile(
  optimizer = "adam",
  loss = "binary_crossentropy",
  metrics = c("accuracy")
)

cnn_history <- simple_cnn_model %>% fit(
  x = x_train,
  y = y_train,
  batch_size = 32,
  epochs = 30,
  validation_split = 0.2
)

plot(cnn_history)

evaluate(simple_cnn_model, x_test, y_test, verbose = 0)

predict_y <- simple_cnn_model %>% predict(x_test)
y_pred=argmax(predict_y, rows=TRUE)
y_pred
y_test
y_testsimplified <- argmax(y_test, rows=TRUE)
y_testsimplified

table(y_pred, y_testsimplified)

